<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ratings".
 *
 * @property int $id
 * @property string $user
 * @property string $page
 * @property int $rating
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ratings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'page', 'rating'], 'required'],
            [['rating'], 'integer'],
            [['user', 'page'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'page' => 'Page',
            'rating' => 'Rating',
        ];
    }
}
