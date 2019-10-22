<?php

namespace saifutdinovalex\arh\models;

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
    
}
