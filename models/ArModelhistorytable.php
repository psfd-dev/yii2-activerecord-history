<?php

namespace backend\components\modelhistory\models;

use Yii;

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
    const TABLE_USER = 2;
    const TABLE_COMPANY = 8;
    
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
}
