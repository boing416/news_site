<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container article-view">
        <div class="row">
            <div class="col-md-3"><img src="\web\<?=$model->img?>"></div>
            <div class="col-md-9"><?=$model->text?></div>
        </div>
    </div>

</div>
