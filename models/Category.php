<?php

namespace app\models;

use Yii;
use app\models\Page;

/**
 * This is the model class for table "categories".
 *
 * @property int $category_id
 * @property int $parent_id
 * @property string $slug
 * @property string $name
 * @property int $access
 *
 * @property Page[] $pages
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'slug', 'name'], 'required'],
            [['parent_id', 'access'], 'integer'],
            [['slug', 'name'], 'string', 'max' => 100],
            [['slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'parent_id' => 'Parent ID',
            'slug' => 'Slug',
            'name' => 'Name',
            'access' => 'Access',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['category' => 'slug']);
    }
}
