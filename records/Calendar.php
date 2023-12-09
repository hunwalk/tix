<?php

namespace app\records;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $background_color
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'background_color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'background_color' => Yii::t('app', 'Background Color'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
