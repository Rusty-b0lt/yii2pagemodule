<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $categories  */
/* @var $pageTags */

$this->title = 'Update Page: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'pageTags' => $pageTags,
    ]) ?>

</div>