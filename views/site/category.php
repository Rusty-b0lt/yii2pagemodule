<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Category */
/* @var $children */
/* @var $pages */
/* @var $query */

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\PageSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\Page;

?>
 <div id="children">
     <?php
        if (count($children) > 0) {
            echo '<h3>Children Categories:</h3> <ul>';
            foreach ($children as $child) {
                echo '<li><h4>' . Html::a($child->name, '/site/page/category/' . $child->slug) . '</h4></li>';
            }
            echo '</ul>';
        }

        if ($model->getPages()->count() !== 0) {
            echo '<h2>Pages</h2>';
            echo GridView::widget([
                    'dataProvider' => new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => ['pageSize' => 10],
                    ]),
                    'filterModel' => new PageSearch(),
                    'rowOptions'   => function ($page, $key, $index, $grid) {
                        return ['data-slug' => $page->slug];
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        'author',
                        //'slug',
                        //'category',
                        'header',
                        //'tags',
                        'creation_date',
                        'mod_date',
                        'rating',
                        //'access',
                        'brief_content:ntext',
                        //'content:ntext',
                    ],

            ]);
            $this->registerJs("
                 $('td').click(function (e) {
                    var slug = $(this).closest('tr').data('slug');
                    if(e.target == this) {
                        location.href = '" . Url::to(['site/page']) . "/' + slug;
                    }
                });
            ");
            $this->registerCss("
                tbody tr:hover td{
                    background-color: #BEBEBE;
                    cursor: pointer;
                }
            ");
        }
     ?>
 </div>