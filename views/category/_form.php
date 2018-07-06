<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories */
?>

<div class="category-form">

    <?php
        $form = ActiveForm::begin();
        $access = ['101' => 'Normal', '110' => 'Unlisted', '111' => 'Admin', '100' => 'Authorized'];
    ?>

    <?= $form->field($model, 'parent_id')->dropDownList($categories) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'access')->dropDownList($access) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
