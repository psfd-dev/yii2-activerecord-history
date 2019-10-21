<?php
/**
 * @link http://mikhailmikhalev.ru
 * @author Mikhail Mikhalev
 */

namespace backend\components\modelhistory\managers;

use Yii;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;


/**
 * Class DBManager for save history in DB
 * @package backend\components\modelhistory
 */
class DBManager extends BaseManager
{
    /**
     * @var string static default for migration
     */
    public static $defaultTableTableName = '{{%modelhistorytable}}';
    public static $defaultTableName = '{{%modelhistory}}';

    /**
     * @var string tableName
     */
    public $tableName;

    /**
     * @var string DB
     */
    public static $db = 'db';

    /**
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function saveField($data)
    {
        $table =  isset($this->tableName) ? $this->tableName : $this::$defaultTableName;
        $obj_table = $this->object->tableName();

        $modeltable = (new Query())->select(['id', 'class'])->from($this::$defaultTableTableName)->where(['table'=>$obj_table])->one();

        if( !$modeltable ) {
            $res = (new Query())->createCommand()
                ->insert($this::$defaultTableTableName, [
                    'table'=>$obj_table,
                    'class' => get_class($this->object)]
                )->execute();
            $modeltable = (new Query())->select(['id', 'class'])->from($this::$defaultTableTableName)->where(['table'=>$obj_table])->one();
        }

        $data['table'] = $modeltable['id'];

        self::getDB()->createCommand()
            ->insert($table, $data)->execute();
    }

    /**
     * @return object Return database connection
     * @throws \yii\base\InvalidConfigException
     */
    private static function getDB()
    {
        return Instance::ensure(self::$db, Connection::class);
    }
}
