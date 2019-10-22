<?php
/**
 * Created by PhpStorm.
 * User: Sergej
 * Date: 07/02/2019
 * Time: 16:51
 */

namespace saifutdinovalex\arh\models;

use saifutdinovalex\arh\managers\DBManager;
use kartik\daterange\DateRangeBehavior;
use yii\base\Model;
use yii\db\Query;

class SearchModel extends Model
{
    /**
     * @var Query
     */
    protected $query;
    const TABLE = 'modelhistory';

    public $table;
//    public $date;
    public $field_name;
    public $user_id;
    public $field_id;
    public $date_range;
    public $date_start;
    public $date_end;

    public function rules()
    {
        return [
            [[
//                'table',
//                'date',
                'field_name',
            ], 'string'],
            [[
                'field_id',
                'user_id',
            ], 'integer'],
            [[
                'table'
            ], 'required'],
            [[
                'date_range'
            ], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'date_range',
                'dateStartAttribute' => 'date_start',
                'dateEndAttribute' => 'date_end',
                'dateStartFormat' => 'Y-m-d',
                'dateEndFormat' => 'Y-m-d',

            ],
        ];
    }

    public function getFields() {
        return [
            'mh.id',
            'mh.date',
            'mhd.table',
            'mhd.class',
            'mhd.permission',
            'mh.field_name',
            'mh.field_id',
            'mh.old_value',
            'mh.new_value',
            'mh.type',
            'mh.user_id',

            "u.surname as username",
        ];
    }

    public function build()
    {
        $this->query = new Query();
        $this->query->select($this->getFields())
            ->from(DBManager::$defaultTableName .' as mh')
            ->leftJoin(DBManager::$defaultTableTableName . 'as mhd', 'mh.table=mhd.id')
            ->leftJoin('User u', 'u.user_id=mh.user_id');

        if(!empty($this->table)) {
            $this->query->andWhere([
                'mhd.table'=>$this->table
            ]);
        }

        if($this->date_start) {
            $this->query->andWhere([
                '>', 'mh.date', $this->date_start . ' 00:00:00'
            ]);
        }
        if($this->date_end) {
            $this->query->andWhere([
                '<', 'mh.date', $this->date_end . ' 23:59:59'
            ]);
        }

        if(!empty($this->field_name)) {
            $this->query->andWhere([
                'mh.field_name'=>$this->field_name
            ]);
        }

        if(!empty($this->user_id)) {
            $this->query->andWhere([
                'mh.user_id'=>$this->user_id
            ]);
        }
        if(!empty($this->field_id)) {
            $this->query->andWhere([
                'mh.field_id'=>$this->field_id
            ]);
        }
        return $this;
    }

    public function getSql() {
        return $this->query->createCommand()->getRawSql();
    }

    public function getCount() {
        return $this->query->count();
    }
}