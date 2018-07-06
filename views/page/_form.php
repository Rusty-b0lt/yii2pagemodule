<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAsset;
use yii\web\View;


AppAsset::register($this);



/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories  */
/* @var $pageTags*/
?>

<div class="page-form">

    <?php
        $form = ActiveForm::begin();
        $access = ['101' => 'Normal', '110' => 'Unlisted', '111' => 'Admin', '100' => 'Authorized'];
        $model->rating = 0;
    ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <label>Tags</label>
    <ul id="tagList">
        <?php
            if ($pageTags !== null) {
                foreach($pageTags as $pageTag) {
                    echo '<li>'. $pageTag['tag'] . '</li>';
                }
            }

        ?>
    </ul>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList($categories) ?>

    <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating')->hiddenInput(['id' => 'rating'])->label(false) ?>

    <?= '<h4>' . Html::a('Set rating to zero', null, ['id' => 'zeroLink']) . '</h4><br>' ?>

    <?= $form->field($model, 'access')->dropDownList($access) ?>

    <?= $form->field($model, 'brief_content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submitButton']) ?>
    </div>

    <?php
        $this->registerJs("
            $('#zeroLink').on('click', function(){
                $('#rating').val(0);
            });
        ");
        $this->registerJs("
            $('#tagList').tagit({fieldName: 'tags[]'});
        ");
        ActiveForm::end();
    ?>

</div>
