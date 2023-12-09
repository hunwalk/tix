<?php

namespace app\records;

use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "system_variable".
 *
 * @property int $id
 * @property string|null $key
 * @property string|null $value
 * @property string|null $value_type
 */
class SystemVariable extends \yii\db\ActiveRecord
{
    const VALUE_TYPE_STRING = 'string';

    const VALUE_TYPE_INTEGER = 'integer';

    const VALUE_TYPE_JSON = 'json';

    const VALUE_TYPE_FLOAT = 'float';

    public static $uses = [];

    public function valueTypes(){
        return [
            self::VALUE_TYPE_STRING => 'String',
            self::VALUE_TYPE_INTEGER => 'Integer',
            self::VALUE_TYPE_JSON => 'JSON',
            self::VALUE_TYPE_FLOAT => 'Float',
        ];
    }

    public static function getValue($key, $default = null, $callback = null){
        $value = $default;

        $variable = static::find()->where(['key' => $key])->one();

        if ($variable){

            if ($callback && is_callable($callback)){
                $value = $callback($variable);
            }

            if ($variable->value_type === static::VALUE_TYPE_STRING){
                $value = $variable->value;
            }

            if ($variable->value_type === static::VALUE_TYPE_JSON){
                $value = json_decode($variable->value,true);
            }

            if ($variable->value_type ===  self::VALUE_TYPE_FLOAT){
                $value = (float) $variable->value;
            }

            if ($variable->value_type ===  self::VALUE_TYPE_INTEGER){
                $value = (int) $variable->value;
            }
        }else{
            $variable = new SystemVariable();
            $variable->key = $key;
            $variable->value = $value;
            $variable->value_type = static::VALUE_TYPE_STRING;

            if (is_int($value)){
                $variable->value_type = static::VALUE_TYPE_INTEGER;
            }

            if (is_float($value)){
                $variable->value_type = static::VALUE_TYPE_FLOAT;
            }

            if (is_array($value)){
                $variable->value = json_encode($value);
                $variable->value_type = static::VALUE_TYPE_JSON;
            }

            $variable->save();
        }

        $bt = debug_backtrace();
        $caller = array_shift($bt);

        $temp = [
            'file' => $caller['file'],
            'line' => $caller['line'],
            'used' => time(),
            'value' => $value,
        ];

        $filename = $key.'_'.md5($caller['file'].':'.$caller['line']);

        if (!is_dir(Yii::getAlias('@runtime/forge'))){
            mkdir(Yii::getAlias('@runtime/forge'));
        }

        if (!is_dir(Yii::getAlias('@runtime/forge/system'))){
            mkdir(Yii::getAlias('@runtime/forge/system'));
        }

        file_put_contents(Yii::getAlias('@runtime/forge/system/'.$filename),json_encode($temp),LOCK_EX);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_variable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['key', 'value_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'value_type' => Yii::t('app', 'Value Type'),
        ];
    }
}
