<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_recipe_style}}".
 *
 * @property int $id
 * @property string $title
 */
class RiRecipeStyle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_recipe_style}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
}
