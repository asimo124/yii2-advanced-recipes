<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_attribute}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property RiRecipeAttribute[] $riRecipeAttributes
 */
class RiAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_attribute}}';
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

    /**
     * Gets query for [[RiRecipeAttributes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiRecipeAttributes()
    {
        return $this->hasMany(RiRecipeAttribute::className(), ['attribute_id' => 'id']);
    }
}
