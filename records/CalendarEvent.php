<?php

namespace app\records;

use Yii;

/**
 * This is the model class for table "calendar_event".
 *
 * @property int $id
 * @property int|null $calendar_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $all_day
 * @property string|null $starts_at
 * @property string|null $ends_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class CalendarEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['calendar_id', 'all_day'], 'integer'],
            [['description'], 'string'],
            [['starts_at', 'ends_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'calendar_id' => Yii::t('app', 'Calendar ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'all_day' => Yii::t('app', 'All Day'),
            'starts_at' => Yii::t('app', 'Starts At'),
            'ends_at' => Yii::t('app', 'Ends At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
