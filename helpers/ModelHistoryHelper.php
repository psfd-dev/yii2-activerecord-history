<?php
/**
 * Created by PhpStorm.
 * User: nikitaignatenkov
 * Date: 26/07/2018
 * Time: 12:09
 */

namespace backend\components\modelhistory\helpers;


use yii\db\ActiveRecord;
use backend\models\ar\ArUsers as User;
use backend\components\modelhistory\interfaces\ModelHistoryInterface;
use yii\base\Exception;
use yii\helpers\Inflector;

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

    public static function getAuthor($data)
    {
        if (isset($data['user_id']) && !empty($data['user_id'])) {
            $model = User::findOne($data['user_id']);
            if ($model) {
                if ($model instanceof ModelHistoryInterface) {
                    /** @var ActiveRecord $model */
                    return $model->formatValue($data['field_name'], $data['user_id']);
                } else {
                    throw new Exception('Please implement ' . ModelHistoryInterface::class . ' on ' . get_class($model));
                }
            }
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
