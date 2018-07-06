<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $author
 * @property string $slug
 * @property string $category
 * @property string $header
 * @property string $creation_date
 * @property string $mod_date
 * @property int $rating
 * @property int $access
 * @property string $brief_content
 * @property string $content
 *
 * @property Category $category0
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author', 'slug', 'category', 'header', 'creation_date', 'mod_date', 'rating', 'access', 'brief_content', 'content'], 'required'],
            [['rating', 'access'], 'integer'],
            [['brief_content', 'content'], 'string'],
            [['author', 'creation_date', 'mod_date'], 'string', 'max' => 12],
            [['slug', 'category'], 'string', 'max' => 100],
            [['header'], 'string', 'max' => 50],
            [['slug'], 'unique'],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'slug']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Author',
            'slug' => 'Slug',
            'category' => 'Category',
            'header' => 'Header',
            'creation_date' => 'Creation Date',
            'mod_date' => 'Mod Date',
            'rating' => 'Rating',
            'access' => 'Access',
            'brief_content' => 'Brief Content',
            'content' => 'Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory0()
    {
        return $this->hasOne(Category::class, ['slug' => 'category']);
    }
    public function getTags() {
        return $this->hasMany(Tag::class, ['tag_id' => 'tag_id'])->viaTable('pages_tags', ['page_id' => 'id']);
    }
}
