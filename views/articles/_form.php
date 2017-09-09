<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>
    <?php
    if ($model->img) {
        echo '<img src="'.\Yii::$app->request->BaseUrl.'/'.$model->img.'" width="90px">&nbsp;&nbsp;&nbsp;';
        echo $model->img .'&nbsp;&nbsp;&nbsp';
        echo Html::a('Удалить файл', ['deleteimg', 'id'=>$model->id, 'field'=> 'img'], ['class'=>'btn btn-danger']).'<p>';
    }
    ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
