<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Articles;
use jino5577\daterangepicker\DateRangePicker;



/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Create Articles', ['value'=>Url::to('index.php?r=articles/create'),'class' => 'btn btn-success','id'=>'modalButton']) ?>
    </p>

    <?php
        Modal::begin([
            'header' => '<h4>Articles</h4>',
            'id' => 'modal',
            'size' => 'modal-lg'
        ]);
        echo "<div id='modalContent'></div>";

        Modal::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'title',
//            'text',
            'desc',
//            'img',

            [
                'attribute' => 'author_id',
                'value' => 'user.username'
            ],
//             'date',
            [
                // the attribute
                'attribute' => 'date',
                // format the value
                'value' => function ($model) {
                    return $model->date;
                },
                // some styling?
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
                // here we render the widget
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_range',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => true
                    ],

                ])
            ],

            [
                'attribute' => 'status',
                'value' => function ($model, $key, $index, $column) {

                    if( $model->author_id == Yii::$app->user->id || Yii::$app->user->can('admin'))
                    {
                        if($model->status == 'active') {
                            return "<select onchange='changeStatus(".$key.",value)'> 
                              <option selected='selected' value='active'>Active</option>
                              <option value='inactive'>Inactive</option>
                            </select>";
                        }
                        else {
                            return "<select onchange='changeStatus(".$key.",value)'> 
                              <option  value='active'>Active</option>
                              <option selected='selected' value='inactive'>Inactive</option>
                            </select>";
                        }
                    }


                },


                'format' =>'raw',

            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:260px;'],
                'header'=>'Actions',
                'template' => '{delete} {update}',
                'buttons' => [

                    //view button
                    'update' => function ($url, $model) {

                        if( $model->author_id == Yii::$app->user->id || Yii::$app->user->can('admin'))
                        {
                            return Html::a('<span class="fa fa-search"></span>Update', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>'btn btn-primary btn-xs',
                        ]);
                        }

                    },
                    'delete' => function ($url, $model) {

                        if( $model->author_id == Yii::$app->user->id || Yii::$app->user->can('admin'))
                        {
                            return Html::a('<span class="fa fa-search"></span>Delete', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class'=>'btn btn-primary btn-xs',
                            ]);
                        }

                    },
                ],



            ],
        ]


    ]); ?>
</div>
