<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'author') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'header') ?>


    <?php // echo $form->field($model, 'creation_date') ?>

    <?php // echo $form->field($model, 'mod_date') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'access') ?>

    <?php // echo $form->field($model, 'brief_content') ?>

    <?php // echo $form->field($model, 'content') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
