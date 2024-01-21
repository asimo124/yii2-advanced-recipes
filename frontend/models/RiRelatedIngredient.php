<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_ingredient}}".
 *
 * @property int $id
 * @property int $ingredient_id
 * @property int $related_ingredient_id
 *
 * @property RiIngredient $ingredient
 * @property RiIngredient $relatedIngredient
 */
class RiRelatedIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_related_ingredient}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ingredient_id', 'related_ingredient_id'], 'required'],
            [['ingredient_id', 'related_ingredient_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ingredient_id' => 'Ingredient ID',
            'ingredient_id' => 'Related Ingredient ID',
        ];
    }

    /**
     * Gets query for [[RiIngredient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasMany(RiIngredient::className(), ['id' => 'ingredient_id']);
    }

    /**
     * Gets query for [[RiIngredient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedIngredient()
    {
        return $this->hasMany(RiIngredient::className(), ['id' => 'related_ingredient_id']);
    }
}
