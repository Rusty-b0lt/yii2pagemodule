<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model app\models\Page */
    /* @var $pageTags*/

    use yii\helpers\Html;
    //use yii\bootstrap\ActiveForm;
    use app\models\Rating;
    use yii\web\View;
    use app\assets\AppAsset;

    AppAsset::register($this);
?>
    <h1><?=Html::encode($model->header)?></h1>
    <ul id="tagList">
        <?php
            if ($pageTags !== null) {
                foreach($pageTags as $pageTag) {
                    echo '<li>' . $pageTag['tag'] . '</li>';
                }
            }
        ?>
    </ul>
    <span>Created on: <?=Html::encode($model->creation_date)?></span>
    <h4><?=$model->content?></h4>

<?php
    $user = Yii::$app->user->identity !== null ? Rating::findOne(['user' => Yii::$app->user->identity->username, 'page' => $model->slug]) : null;
    if (Yii::$app->user->isGuest || $user !== null) {
        $rating = $user !== null ? $user->rating : $model->rating;
        echo '<div class="rating">';
        for ($i = 1; $i <= $rating; $i++) {
            echo '<span style="font-size: 200%;">★</span>';
        }
        for ($i = 1; $i <= 5 - $rating; $i++) {
            echo '<span style="font-size: 200%;">☆</span>';
        }
        echo '</div>';
    }
    else {
        $rating = $model->rating;
        echo '<div class="rating">';
        for ($i = 1; $i <= 5 - $rating; $i++) {
            echo '<span style="font-size: 200%;" class="clearStar star">☆</span>';
        }
        for ($i = 1; $i <= $rating; $i++) {
            echo '<span style="font-size: 200%;" class="blackStar star">★</span>';
        }
        echo '</div>';

        $this->registerCss('
            .rating {
              unicode-bidi: bidi-override;
              direction: rtl;
              text-align: left;
            }
            .rating > span {
              display: inline-block;
              position: relative;
              width: 1.1em;
            }
            .rating > span:hover,
            .rating > span:hover ~ span {
              color: transparent;
            }
            .rating > span:hover:before,
            .rating > span:hover ~ span:before {
               content: "\2605";
               position: absolute;
               left: 0; 
               color: black;
            }
        ');
        $this->registerJs("
                $('.blackStar').hover(function() {
                    $(this).text('☆');
                });
                $('.star').click(function() {
                    var stars = $('.star');
                    var rating = (stars.length - stars.index(this));
                    $.get('/site/rating', {rating: rating, page: window.location.pathname});
                    $('.rating').replaceWith('Thank you for voting!');
                   
                });
        ",
        View::POS_READY
        );
    }
    $this->registerJs("
        $('#tagList').tagit({readOnly: true});
    ");
    $this->registerCss("
        #tagList {
                border: 0;
        }
    ");
?>