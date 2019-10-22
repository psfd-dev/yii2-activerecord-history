<?php
/**
 * Created by PhpStorm.
 * User: nikitaignatenkov
 * Date: 03/08/2018
 * Time: 18:20
 */

use psfd\arh\helpers\ModelHistoryHelper;

?>
<style>
    .grid-view td {
        white-space: normal;
    }
</style>

<h3>История изменений</h3>
<?php echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'date',
            'header' => 'Дата',
            'format' => 'datetime',
            'filter' => \kartik\daterange\DateRangePicker::widget([
                'model' => $searchModel,
                'attribute' => 'date_range',
                'startAttribute'=>'date_start',
                'endAttribute'=>'date_end',
                'convertFormat'=>false,
                'language' => 'ru',
                'options' => ['class' => 'form-control'],
                'pluginOptions' => [
                    'locale' => [
                        'format' => 'D.M.Y',
                    ],
                    'opens'=>'left',
                    'timePicker' => false,
                    'autoclose' => true,
                    'todayHighlight' => true,
                ]
            ]),
        ],
        [
            'attribute' => 'field_id',
            'label' => 'Объект',
            'content' => function ($model) {

                return \yii\helpers\Html::a(ucfirst($model['table']) ." #".$model['field_id'], ModelHistoryHelper::getEditLink($model));
            },
        ],
        [
            'attribute' => 'field_name',
            'header' => 'Поле',
            'value' => function ($data) {
                return ModelHistoryHelper::formatLabel($data);
            }
        ],
        [
            'attribute' => 'old_value',
            'header' => 'Старое значение',
            'format' => 'raw',
            'value' => function ($data) {
                return ModelHistoryHelper::formatValue($data, $data['old_value']);
            }
        ],
        [
            'attribute' => 'new_value',
            'header' => 'Новое значение',
            'format' => 'raw',
            'value' => function ($data) {
                return ModelHistoryHelper::formatValue($data, $data['new_value']);
            }
        ],
        [
            'attribute' => 'username',
            'header' => 'Пользователь',
        ]
    ],
]);
?>
