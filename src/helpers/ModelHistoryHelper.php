<?php
/**
 * Created by PhpStorm.
 * User: nikitaignatenkov
 * Date: 26/07/2018
 * Time: 12:09
 */

namespace saifutdinovalex\arh\helpers;


use yii\db\ActiveRecord;
use yii\base\Exception;
use yii\helpers\Inflector;

use saifutdinovalex\arh\interfaces\ModelHistoryInterface;

class ModelHistoryHelper
{
    /**
     * @param $data
     * @param $value
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function formatValue($data, $value)
    {
        $object = self::getObjectFromTable($data['class']);
        if ($object instanceof ModelHistoryInterface) {
            /** @var ActiveRecord $model */
            $model = $object::findOne($data['field_id']);
            return $model->formatValue($data['field_name'], $value);
        } else {
            throw new Exception('Please implement ' . ModelHistoryInterface::class . ' on ' . get_class($object));
        }
    }

    public static function getEditLink($data)
    {
        $object = self::getObjectFromTable($data['class']);
        return $object->getEditLink($data['field_id']);
    }

    public static function formatLabel($data)
    {
        $object = self::getObjectFromTable($data['class']);
        return $object->getAttributeLabel($data['field_name']);

    }

    private static function getObjectFromTable($class)
    {
        $object = \Yii::createObject(['class' => $class]);
        return $object;
    }

    public static function clearTableName($value)
    {
        $value = str_replace('{{%', '', $value);
        $value = str_replace('}}', '', $value);
        return $value;
    }
}
