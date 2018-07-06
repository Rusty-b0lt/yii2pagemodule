<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $tag_id
 * @property string $tag
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag'], 'required'],
            [['tag'], 'string', 'max' => 50],
            [['tag'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'tag' => 'Tag',
        ];
    }
    public function getPages()
    {
        return $this->hasMany(Page::class, ['id' => 'page_id'])->viaTable('pages_tags', ['tag_id' => 'tag_id']);
    }
}
