<?php

namespace app\commands;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use JDecool\Clockify\Api\TimeEntry\StopTimeEntryRequest;
use JDecool\Clockify\Api\TimeEntry\TimeEntriesDurationRequest;
use JDecool\Clockify\Api\TimeEntry\TimeEntry;
use JDecool\Clockify\Api\TimeEntry\TimeEntryRequest;
use JDecool\Clockify\Api\User\User;
use Yii;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\httpclient\Client;

class ClockifyController extends \yii\console\Controller
{
    public $config;

    private $apiFactory;
    const CLOCKIFY_CONFIG_PATH = '@app/config/clockify.json';

    const DEFAULT_WORKSPACE_SELECTOR = 'DEFAULT_WORKSPACE_ID';

    const DEFAULT_PROJECTS_SELECTOR = 'DEFAULT_PROJECTS';

    const PROJECT_SELECTOR = 'PROJECT_ID';

    const WORKSPACE_SELECTOR = 'WORKSPACE_ID';

    const SELECTOR_CONFIG = [
        self::DEFAULT_WORKSPACE_SELECTOR => 'single',
        self::DEFAULT_PROJECTS_SELECTOR => 'array',
        self::PROJECT_SELECTOR => 'single',
    ];

    const AUTOWIRE = [
        'DEFAULT_WORKSPACE_ID' => 'selectDefaultWorkspace',
    ];

    const AUTOPROMPT = [
        'PROJECT_ID' => 'selectProject',
        'WORKSPACE_ID' => 'selectWorkspace',
    ];

    public function init()
    {
        $builder = new \JDecool\Clockify\ClientBuilder();
        $client = $builder->createClientV1($_ENV['CLOCKIFY_API_KEY']);

        $this->apiFactory = new \JDecool\Clockify\ApiFactory($client);
        $this->loadConfig();
    }

    public function loadConfig(){
        if (!file_exists(Yii::getAlias(self::CLOCKIFY_CONFIG_PATH))){
            file_put_contents(Yii::getAlias(self::CLOCKIFY_CONFIG_PATH), json_encode([], JSON_PRETTY_PRINT));
        }
        $this->config = json_decode(file_get_contents(Yii::getAlias(self::CLOCKIFY_CONFIG_PATH)), true);
    }

    public function saveConfig(){
        file_put_contents(Yii::getAlias(self::CLOCKIFY_CONFIG_PATH), json_encode($this->config, JSON_PRETTY_PRINT));
    }

    public function getConfig($key){
        if (array_key_exists($key, self::AUTOPROMPT)){
            if (self::AUTOPROMPT[$key] && method_exists($this, self::AUTOPROMPT[$key])){
                $method = self::AUTOPROMPT[$key];
                return $this->$method();
            }else{
                return $this->prompt('Enter ' . $key . ': ');
            }
        }

        if (array_key_exists($key, $this->config)){
            return $this->config[$key];
        }

        if (array_key_exists($key, self::AUTOWIRE)){
            $method = self::AUTOWIRE[$key];
            $this->$method();
            return $this->config[$key];
        }

        return null;
    }

    public function actionWorkspaces(){
        $this->listWorkspaces();
        return ExitCode::OK;
    }

    public function actionProjects(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        $this->listProjects($workspaceId);
        return ExitCode::OK;
    }

    public function actionCurrent(){
        $this->listRunningTimeEntries();
        return ExitCode::OK;
    }

    public function actionCurrentMonth(){
        $this->listCurrentMonthTimeEntries();
        return ExitCode::OK;
    }

    public function actionCurrentMonthUntracked(){
        $this->listCurrentMonthUntracked();
        return ExitCode::OK;
    }

    public function actionPreviousMonthUntracked(){
        $this->listPreviousMonthUntracked();
        return ExitCode::OK;
    }

    public function actionAddDefaultProject(){
        $this->addDefaultProject();
        return ExitCode::OK;
    }

    /**
     * Start time entry for a project
     */
    public function actionStart(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        $startDate = Carbon::now()->toIso8601ZuluString();

        $projectId = $this->getConfig(self::PROJECT_SELECTOR);
        $description = $this->prompt('Enter description: ');
        $timeInterval = new TimeEntriesDurationRequest($startDate,null);
        $timeEntryRequest = new TimeEntryRequest(null, $startDate, true, $description, $projectId, null, null, null, [], $timeInterval, $workspaceId, false);


        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();
        $response = $timeEntryApi->create($workspaceId, $timeEntryRequest);

        $this->stdout("Started time entry: \n");
        $rows = [];

        $description = $response->description();
        $started = Carbon::parse($response->timeInterval()->start());

        $rows[] = [$response->id(), $description, $started->diffForHumans()];

        echo Table::widget([
            'headers' => ['ID', 'Description', 'Started'],
            'rows' => $rows,
        ]);

        return ExitCode::OK;
    }

    public function actionEnd(){
        $timeEntryApi = $this->apiFactory->timeEntryApi();
        $userApi = $this->apiFactory->userApi();

        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);
        $userId = $userApi->current()->id();

        $this->listRunningTimeEntries();
        if ($this->confirm('Are you sure to stop the time entry above?')){
            $stopTimeEntryRequest = new StopTimeEntryRequest(Carbon::now()->toIso8601ZuluString());
            $timeEntryApi->stopRunningTime($workspaceId, $userId, $stopTimeEntryRequest);
            $this->stdout("Stopped time entry \n");
        }
    }

    public function actionEntry($params = ''){
        if ($params){
            $params = explode(',', $params);
        }

        $workspaceId = $this->getConfig(self::WORKSPACE_SELECTOR);
        $projectId = $this->selectProject($workspaceId);
        $description = $this->prompt('Enter description: ');

        if($params){
            $startDate = Carbon::parse($params[0])->timezone('Europe/Bratislava');
            $endDate = Carbon::parse($params[0])->timezone('Europe/Bratislava');
            $startTime = $params[1];
            $endTime = $params[2];
        }else{
            if ($this->confirm('Use today?')){
                $startDate = Carbon::now()->timezone('Europe/Bratislava');
                $endDate = Carbon::now()->timezone('Europe/Bratislava');
            }else{
                $day = Carbon::parse($this->prompt('Enter day (YYYY-MM-DD): '))->timezone('Europe/Bratislava');
                $startDate = $day;
                $endDate = $day;
            }

            $startTime = $this->prompt('Enter start time (HH:MM): ');
            $endTime = $this->prompt('Enter end time (HH:MM): ');
        }


        //add colon if missing
        if (strlen($startTime) == 4){
            $startTime = substr($startTime, 0, 2) . ':' . substr($startTime, 2);
        }
        if (strlen($endTime) == 4){
            $endTime = substr($endTime, 0, 2) . ':' . substr($endTime, 2);
        }

        $startDate = $startDate->setTimeFromTimeString($startTime)->toIso8601ZuluString();
        $endDate = $endDate->setTimeFromTimeString($endTime)->toIso8601ZuluString();

        $timeInterval = new TimeEntriesDurationRequest($startDate,$endDate);
        $timeEntryRequest = new TimeEntryRequest(null, $startDate, true, $description, $projectId, null, null, $endDate, [], $timeInterval, $workspaceId, false);

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $response = $timeEntryApi->create($workspaceId, $timeEntryRequest);

        $this->stdout("Created time entry: \n");
        $rows = [];

        $description = $response->description();
        $started = Carbon::parse($response->timeInterval()->start());
        $ended = Carbon::parse($response->timeInterval()->end());

        $rows[] = [$response->id(), $description, $started->diffForHumans(), $ended->diffForHumans()];

        echo Table::widget([
            'headers' => ['ID', 'Description', 'Started', 'Ended'],
            'rows' => $rows,
        ]);

        return ExitCode::OK;
    }

    public function actionExport(){
        $this->stdout("Which workspace do you want to export? \n");
        $workspaceId = $this->getConfig(self::WORKSPACE_SELECTOR);

        $this->stdout("Which project do you want to export? \n");
        $projectId = $this->selectProject($workspaceId);

        $this->stdout("Export start date? \n");
        $startDate = $this->prompt('Enter start date (YYYY-MM-DD): ');
        $startDate = Carbon::parse($startDate)->startOfDay()->toIso8601ZuluString();

        $this->stdout("Export end date? \n");
        $endDate = $this->prompt('Enter end date (YYYY-MM-DD): ');
        $endDate = Carbon::parse($endDate)->endOfDay()->toIso8601ZuluString();

        $this->stdout("Export will be saved at: ".Yii::getAlias('@app/runtime/export.csv')."\n");

        $this->export($workspaceId, $projectId, $startDate, $endDate);
    }

    public function actionImport(){
        $this->stdout("Which workspace do you want to import to? \n");
        $targetWorkspaceId = $this->getConfig(self::WORKSPACE_SELECTOR);

        $this->stdout("Which project do you want to import to? \n");
        $targetProjectId = $this->selectProject($targetWorkspaceId);

        // search for export.csv
        $this->stdout("Searching for export.csv in ".Yii::getAlias('@app/runtime')."\n");
        $files = scandir(Yii::getAlias('@app/runtime'));

        $exportFile = null;

        foreach ($files as $file){
            if ($file == 'export.csv'){
                $exportFile = $file;
                break;
            }
        }

        if (!$exportFile){
            $this->stdout("No export.csv found in ".Yii::getAlias('@app/runtime')."\n");
            return;
        }

        $this->stdout("Found export.csv in ".Yii::getAlias('@app/runtime')."\n");

        $filePath = Yii::getAlias('@app/runtime/'.$exportFile);

        $this->import($filePath, $targetWorkspaceId, $targetProjectId);
    }

    public function import($filePath, $targetWorkspaceId, $targetProjectId){
        $timeEntryApi = $this->apiFactory->timeEntryApi();
        $userApi = $this->apiFactory->userApi();

        $userId = $userApi->current()->id();

        $fp = fopen($filePath, 'r');

        $rows = [];

        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }

        fclose($fp);

        $this->stdout("Importing ".count($rows)." time entries \n");

        foreach ($rows as $row){
            $start = Carbon::parse($row[3])->toIso8601ZuluString();
            $end = Carbon::parse($row[4])->toIso8601ZuluString();
            $timeInterval = new TimeEntriesDurationRequest($start,$end);
            $timeEntryRequest = new TimeEntryRequest(null, $start, true, $row[2], $targetProjectId, null, null, $end, [], $timeInterval, $targetWorkspaceId, false);
            $timeEntryApi->create($targetWorkspaceId, $timeEntryRequest);
        }
    }

    protected function export($workspaceId, $projectId, $startDate, $endDate){
        $timeEntryApi = $this->apiFactory->timeEntryApi();
        $userApi = $this->apiFactory->userApi();

        $userId = $userApi->current()->id();

        $response = $timeEntryApi->find($workspaceId, $userId, [
            'start' => $startDate,
            'end' => $endDate,
        ]);

        $rows = [];

        foreach ($response as $timeEntry){
            $timeEntryStart = Carbon::parse($timeEntry->timeInterval()->start());
            $timeEntryEnd = Carbon::parse($timeEntry->timeInterval()->end());
            $duration = $timeEntryStart->diffInSeconds($timeEntryEnd);
            $rows[] = [
                $timeEntry->id(),
                $timeEntry->projectId(),
                $timeEntry->description(),
                $timeEntryStart->toDateTimeString(),
                $timeEntryEnd->toDateTimeString(),
                $duration,
            ];
        }

        $fp = fopen(Yii::getAlias('@app/runtime/export.csv'), 'w');

        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }

    protected function selectDefaultWorkspace(){
        $this->listWorkspaces();
        $workspaceId = $this->prompt('Select workspace ID: ');
        $this->config[self::DEFAULT_WORKSPACE_SELECTOR] = $workspaceId;
        $this->saveConfig();
    }

    protected function addDefaultProject(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);
        $this->listProjects($workspaceId);
        $projectId = $this->prompt('Select project ID: ');
        $projectName = $this->prompt('Enter project name: ');
        $this->config[self::DEFAULT_PROJECTS_SELECTOR][] = [
            'workspaceId' => $workspaceId,
            'projectId' => $projectId,
            'name' => $projectName
        ];
        $this->saveConfig();
    }

    /**
     * Autoprompts workspace id
     * @return string
     */
    protected function selectWorkspace(){
        $this->listWorkspaces();
        return $this->prompt('Select workspace ID: ');
    }


    /**
     * Autoprompts project id
     * @return string
     */
    protected function selectProject($workspaceId = null){
        if (!$workspaceId)
            $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        $defaultProjects = $this->getConfig(self::DEFAULT_PROJECTS_SELECTOR);
        if ($defaultProjects){
            $this->listDefaultProjects();
        }else{
            $this->stdout("No default projects found. Listing all projects: \n\n");
            $this->listProjects($workspaceId);
            return $this->prompt('Select project ID: ');
        }

        $favoritePrompt = $this->prompt('Select project ID (Enter `all` to list all): ');

        if ($favoritePrompt != 'all'){
            if (isset($defaultProjects[$favoritePrompt])){
                return $defaultProjects[$favoritePrompt]['projectId'];
            }
        }

        $this->listProjects($workspaceId);
        return $this->prompt('Select project ID: ');
    }

    protected function listCurrentMonthUntracked(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        /** @var User $userApi */
        $userApi = $this->apiFactory->userApi();

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $start = Carbon::now()->startOfMonth()->toIso8601ZuluString();
        $end = Carbon::now()->endOfMonth()->toIso8601ZuluString();

        $userId = $userApi->current()->id();

        $response = $timeEntryApi->find($workspaceId, $userId, [
            'start' => $start,
            'end' => $end,
        ]);

        //list all weekdays that has no time entry

        $this->stdout("Current month untracked days: \n");

        //iterator
        $days = new DatePeriod(
            Carbon::now()->startOfMonth(),
            CarbonInterval::day(),
            Carbon::now()->endOfMonth()
        );

        $rows = [];

        foreach ($days as $day){
            $isWeekday = Carbon::instance($day)->isWeekday();
            $hasTimeEntry = false;

            foreach ($response as $timeEntry){
                $timeEntryStart = Carbon::parse($timeEntry->timeInterval()->start());
                if ($timeEntryStart->isSameDay($day)){
                    $hasTimeEntry = true;
                    break;
                }
            }

            if ($isWeekday && !$hasTimeEntry){
                $rows[] = [$day->format('Y-m-d')];
            }
        }

        echo Table::widget([
            'headers' => ['Date'],
            'rows' => $rows,
        ]);
    }

    public function listPreviousMonthUntracked(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        /** @var User $userApi */
        $userApi = $this->apiFactory->userApi();

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $start = Carbon::now()->subMonth()->startOfMonth()->toIso8601ZuluString();
        $end = Carbon::now()->subMonth()->endOfMonth()->toIso8601ZuluString();

        $userId = $userApi->current()->id();

        $response = $timeEntryApi->find($workspaceId, $userId, [
            'start' => $start,
            'end' => $end,
        ]);

        //list all weekdays that has no time entry

        $this->stdout("Previous month untracked days: \n");

        //iterator
        $days = new DatePeriod(
            Carbon::now()->subMonth()->startOfMonth(),
            CarbonInterval::day(),
            Carbon::now()->subMonth()->endOfMonth()
        );

        $rows = [];

        foreach ($days as $day){
            $isWeekday = Carbon::instance($day)->isWeekday();
            $hasTimeEntry = false;

            foreach ($response as $timeEntry){
                $timeEntryStart = Carbon::parse($timeEntry->timeInterval()->start());
                if ($timeEntryStart->isSameDay($day)){
                    $hasTimeEntry = true;
                    break;
                }
            }

            if ($isWeekday && !$hasTimeEntry){
                $rows[] = [$day->format('Y-m-d')];
            }
        }

        echo Table::widget([
            'headers' => ['Date'],
            'rows' => $rows,
        ]);
    }

    public function getPreviousMonthUntracked(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        /** @var User $userApi */
        $userApi = $this->apiFactory->userApi();

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $start = Carbon::now()->subMonth()->startOfMonth()->toIso8601ZuluString();
        $end = Carbon::now()->subMonth()->endOfMonth()->toIso8601ZuluString();

        $userId = $userApi->current()->id();

        $response = $timeEntryApi->find($workspaceId, $userId, [
            'start' => $start,
            'end' => $end,
        ]);

        //list all weekdays that has no time entry

        $this->stdout("Previous month untracked days: \n");

        //iterator
        $days = new DatePeriod(
            Carbon::now()->subMonth()->startOfMonth(),
            CarbonInterval::day(),
            Carbon::now()->subMonth()->endOfMonth()
        );

        $rows = [];

        foreach ($days as $day){
            $isWeekday = Carbon::instance($day)->isWeekday();
            $hasTimeEntry = false;

            foreach ($response as $timeEntry){
                $timeEntryStart = Carbon::parse($timeEntry->timeInterval()->start());
                if ($timeEntryStart->isSameDay($day)){
                    $hasTimeEntry = true;
                    break;
                }
            }

            if ($isWeekday && !$hasTimeEntry){
                $rows[] = $day->format('Y-m-d');
            }
        }

        return $rows;
    }

    protected function listCurrentMonthTimeEntries(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        /** @var User $userApi */
        $userApi = $this->apiFactory->userApi();

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $start = Carbon::now()->startOfMonth()->toIso8601ZuluString();
        $end = Carbon::now()->endOfMonth()->toIso8601ZuluString();

        $userId = $userApi->current()->id();
        $response = $timeEntryApi->find($workspaceId, $userId, [
            'start' => $start,
            'end' => $end,
        ]);

        $this->stdout("Current month time entries: \n");
        $rows = [];

        $currentTrackedDay = null;
        $dailyTasksDuration = 0;

        foreach ($response as $index => $timeEntry){
            $projectId = $timeEntry->projectId();
            $description = $timeEntry->description();
            $started = Carbon::parse($timeEntry->timeInterval()->start())->toDateTimeString() . ' (' . Carbon::parse($timeEntry->timeInterval()->start())->diffForHumans() . ')';
            $ended = Carbon::parse($timeEntry->timeInterval()->end())->toDateTimeString() . ' (' . Carbon::parse($timeEntry->timeInterval()->end())->diffForHumans() . ')';
            $duration = Carbon::parse($timeEntry->timeInterval()->start())->diffInMinutes(Carbon::parse($timeEntry->timeInterval()->end())) . ' minutes';

            if ($currentTrackedDay !== Carbon::parse($timeEntry->timeInterval()->start())->toDateString()){
                if ($currentTrackedDay !== null){
                    $dailyTasksDurationHours = floor($dailyTasksDuration / 60);
                    $dailyTasksDurationMinutes = $dailyTasksDuration % 60;
                    $rows[] = ['','','',$currentTrackedDay,'Total', $dailyTasksDurationHours . ' hours ' . $dailyTasksDurationMinutes . ' minutes'];
                    $rows[] = ['','','','','',''];
                }
                $currentTrackedDay = Carbon::parse($timeEntry->timeInterval()->start())->toDateString();
                $dailyTasksDuration = Carbon::parse($timeEntry->timeInterval()->start())->diffInMinutes(Carbon::parse($timeEntry->timeInterval()->end()));
                $rows[] = [$timeEntry->id(), $projectId, $description, $started, $ended, $duration];
            }else{
                $dailyTasksDuration += Carbon::parse($timeEntry->timeInterval()->start())->diffInMinutes(Carbon::parse($timeEntry->timeInterval()->end()));
                $rows[] = [$timeEntry->id(), $projectId, $description, $started, $ended, $duration];
            }

            if ($index === count($response) - 1){
                $dailyTasksDurationHours = floor($dailyTasksDuration / 60);
                $dailyTasksDurationMinutes = $dailyTasksDuration % 60;
                $rows[] = ['','','',$currentTrackedDay,'Total', $dailyTasksDurationHours . ' hours ' . $dailyTasksDurationMinutes . ' minutes'];
            }

        }

        //last entry


        echo Table::widget([
            'headers' => ['ID', 'Project ID', 'Description', 'Started', 'Ended', 'Duration'],
            'rows' => $rows,
        ]);
    }

    protected function listRunningTimeEntries(){
        $workspaceId = $this->getConfig(self::DEFAULT_WORKSPACE_SELECTOR);

        /** @var User $userApi */
        $userApi = $this->apiFactory->userApi();

        /** @var TimeEntry $timeEntryApi */
        $timeEntryApi = $this->apiFactory->timeEntryApi();

        $userId = $userApi->current()->id();
        $response = $timeEntryApi->find($workspaceId, $userId, []);

        $currentTimeEntry = $response[0];

        //check if last time entry has end
        if ($currentTimeEntry->timeInterval()->end() !== null){
            $this->stdout("No running time entry found.\n");
            return;
        }

        $this->stdout("Current time entry: \n");
        $rows = [];

        $projectId = $currentTimeEntry->projectId();
        $description = $currentTimeEntry->description();
        $started = Carbon::parse($currentTimeEntry->timeInterval()->start())->toDateTimeString() . ' (' . Carbon::parse($currentTimeEntry->timeInterval()->start())->diffForHumans() . ')';

        $rows[] = [$projectId, $description, $started];

        echo Table::widget([
            'headers' => ['Project', 'Description', 'Started'],
            'rows' => $rows,
        ]);
    }

    protected function listProjects($workspaceId){
        $projectApi = $this->apiFactory->projectApi();
        $projects = $projectApi->projects($workspaceId,['page-size' => 500]);

        $rows = [];

        foreach ($projects as $project){
            $rows[] = [$project->id(), $project->name()];
        }

        echo Table::widget([
            'headers' => ['ID', 'Name'],
            'rows' => $rows,
        ]);
    }

    protected function listDefaultProjects(){
        $defaultProjects = $this->getConfig(self::DEFAULT_PROJECTS_SELECTOR);

        $rows = [];

        foreach ($defaultProjects as $index => $defaultProject){
            $rows[] = [$index, $defaultProject['name']];
        }

        echo Table::widget([
            'headers' => ['ID', 'Name'],
            'rows' => $rows,
        ]);
    }

    protected function listWorkspaces(){
        $workspaceApi = $this->apiFactory->workspaceApi();
        $workspaces = $workspaceApi->workspaces();

        $rows = [];

        foreach ($workspaces as $workspace){
            $rows[] = [$workspace->id(), $workspace->name()];
        }

        echo Table::widget([
            'headers' => ['ID', 'Name'],
            'rows' => $rows,
        ]);
    }
}