<?php

namespace psfd\arh\models;

use Yii;
use psfd\arh\helpers\ModelHistoryHelper;
/**
 * This is the model class for table "modelhistorytable".
 *
 * @property int $id
 * @property string $table
 * @property string $class
 * @property string $permission
 *
 * @property Modelhistory[] $modelhistories
 */
class ArModelhistorytable extends \yii\db\ActiveRecord
{
    const TABLE_CACHE_KEY = 'table_cache_key';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modelhistorytable';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelhistories()
    {
        return $this->hasMany(Modelhistory::className(), ['table' => 'id']);
    }

    public static function getById($table)
    {
        return self::find()->andWhere(['table' => $table])->one();
    }

    public static function getId($table)
    {
        return self::getById($table)->id;
    }

    public static function getKeyTable($tableName)
    {
        $cache = new \yii\caching\FileCache;

        $data = $cache->get(static::TABLE_CACHE_KEY);

        if ($data === false) {
            $data = self::addTableCache();        
        }
        $data = json_decode($data);

        if ($data && isset($data->$tableName))
        {
            return $data->$tableName;
        }

        throw new \Exception("Not exist key table in cache", 500);
    }

    public static function getTableData()
    {
        $models = self::find()->all();
        $result = \yii\helpers\ArrayHelper::map($models, 'table', 'id');
        $new = [];
        foreach ($result as $key => $value) 
        {
            $new[ModelHistoryHelper::clearTableName($value['table'])] = $value['id'];    
        }
        $data = json_encode($new);
        return $data;
    }

    public static function addTableCache()
    {
        $cache = new \yii\caching\FileCache;
        $data = self::getTableData();
        $cache->set(static::TABLE_CACHE_KEY, $data);
        return $data;
    }


}
