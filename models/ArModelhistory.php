<?php

namespace backend\components\modelhistory\models;

use Yii;

/**
 * This is the model class for table "modelhistory".
 *
 * @property int $id
 * @property string $date
 * @property int $table
 * @property string $field_name
 * @property string $field_id
 * @property string $old_value
 * @property string $new_value
 * @property int $type
 * @property int $user_id
 *
 * @property Modelhistorytable $table0
 */
class ArModelhistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modelhistory';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'table' => 'Table',
            'field_name' => 'Field Name',
            'field_id' => 'Field ID',
            'old_value' => 'Old Value',
            'new_value' => 'New Value',
            'type' => 'Type',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTable0()
    {
        return $this->hasOne(Modelhistorytable::className(), ['id' => 'table']);
    }

    public function setPeriod($period)
    {
        return $this->andWhere(['between', 'date', $start, $end]);
    }

    public function setTalbe($table_id)
    {
        return $this->andWhere(['table' => $table_id]);
    }

    public function setFieldName($field_array)
    {
        return $this->andWhere(['field_name' => $field_array]);
    }

    public function setOldValue($value)
    {
        return $this->andWhere(['old_value' => $value]);   
    }

    public function setNewValue($value)
    {
        return $this->andWhere(['new_value' => $value]);
    }

    public function setFieldId($value)
    {
        return $this->andWhere(['field_id' => $value]);
    }
    /*
    берем данные только по статусам, которые были созданы в этот период.
    Т.е. изначально new_value = 0
    */
    public static function getVpRequestIdStatusNew($period)
    {
        $model = self::find()->select(['field_id'])
        ->andWhere(['between', 'date', $period[0], $period[1]])
        ->andWhere(['table' => 5])
        ->andWhere(['field_name' => 'status'])
        ->andWhere(['new_value' => 0])
        ->groupBy(['field_id'])
        ->column();

        return $model;
    }

    public static function getCountVpRequestStatus($vp_request_ids, $status_id, $period)
    {
        $model = self::find()
        ->andWhere(['between', 'date', $period[0], $period[1]])
        ->andWhere(['table' => 5])
        ->andWhere(['field_name' => 'status'])
        ->andWhere(['new_value' => $status_id])
        ->andWhere(['field_id' => $vp_request_ids])
        ->groupBy(['field_id'])
        ->count();

        return $model;
    }

    public static function getCountVpRequestStatusPeriod($status_id, $period)
    {
        $model = self::find()
        ->andWhere(['between', 'date', $period[0], $period[1]])
        ->andWhere(['table' => 5])
        ->andWhere(['field_name' => 'status'])
        ->andWhere(['new_value' => $status_id])
        ->groupBy(['field_id'])
        ->count();

        return $model;
    }

    public function getUsersData()
    {
        return $this->hasOne(\backend\models\ar\ArUsers::class, ['user_id' => 'user_id']);
    }
}
