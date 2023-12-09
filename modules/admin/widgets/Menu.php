<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
    public $items = [];
    public $route;
    public $params;
    public $noDefaultAction;
    public $noDefaultRoute;

    public function renderLayout($content){
        return Html::tag('ul',$content,[
            'class' => 'nav nav-pills nav-sidebar flex-column',
            'data-widget' => 'treeview',
            'role' => 'menu',
            'data-accordion' => 'false'
        ]);
    }

    public function renderItem($item,$level = 0){
        if (is_string($item))
            return Html::tag('li', $item, ['class' => 'nav-header']);

        if (is_array($item)){

            if (is_array($item)){
                $url = \yii\helpers\ArrayHelper::getValue($item,'url');
                $icon = \yii\helpers\ArrayHelper::getValue($item,'icon');
                $name = \yii\helpers\ArrayHelper::getValue($item,'name');
                $active = \yii\helpers\ArrayHelper::getValue($item,'active');
                $visible = \yii\helpers\ArrayHelper::getValue($item,'visible',true);
                $items = \yii\helpers\ArrayHelper::getValue($item,'items');
                $linkOptions = \yii\helpers\ArrayHelper::getValue($item,'linkOptions',[]);
            }

            if ($visible){

                $itemOutput = Html::beginTag('li', ['class' => [
                    'nav-item', $items && $active ? 'menu-open' : null,
                ]]);

                $itemOutput .= Html::a(
                    Html::tag('i','',['class'=>'nav-icon fas fa-'.$icon]).
                    Html::tag('p',$name.($items ? Html::tag('i','',['class' => 'right fas fa-angle-left']) : null))
                    ,$url,array_merge($linkOptions,['class' => ['nav-link',$active ? 'active' : null]]));

                if ($items){
                    $itemOutput .= Html::beginTag('ul',[
                        'class' => 'nav nav-treeview subnav '.($active ? 'active' : ''),
                        'style' => [
                            'border-left' => '3px solid #007bff',
                            'margin-top' => '4px',
                            'padding-left' => '5px',
                        ]
                    ]);
                    foreach ($items as $item){
                        $itemOutput .= $this->renderItem($item);
                    }
                    $itemOutput .= Html::endTag('ul');
                }

                $itemOutput .= Html::endTag('li');

                return $itemOutput;
            }

        }
        return null;
    }

    public function run(){

        if ($this->route === null && Yii::$app->controller !== null) {
            $urlManager = Yii::$app->urlManager;
            $this->route = $urlManager->createUrl([Yii::$app->controller->getRoute()]);
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $posDefaultAction = strpos($this->route, Yii::$app->controller->defaultAction);
        if ($posDefaultAction) {
            $this->noDefaultAction = rtrim(substr($this->route, 0, $posDefaultAction), '/');
        } else {
            $this->noDefaultAction = false;
        }
        $posDefaultRoute = strpos($this->route, Yii::$app->controller->module->defaultRoute);
        if ($posDefaultRoute) {
            $this->noDefaultRoute = rtrim(substr($this->route, 0, $posDefaultRoute), '/');
        } else {
            $this->noDefaultRoute = false;
        }

        $itemOutput = '';

        foreach ($this->items as $item){
            $active = $this->isItemActive($item);
            $item['active'] = $active;

            if (array_key_exists('items',$item)){
                foreach ($item['items'] as $index => $subItem){
                    $item['items'][$index]['active'] = $this->isItemActive($subItem);
                }
            }

            $itemOutput .= $this->renderItem($item);
        }

        return $this->renderLayout($itemOutput);

    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];

            if (strpos($this->route,$route) === false){
                return false;
            }

            unset($item['url']['#']);

            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

}