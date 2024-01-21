<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%tv_mood}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property TvShowMood[] $tvShowMoods
 */
class TvMood extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tv_mood}}';
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
            'display_order' => 'Display Order'
        ];
    }

    /**
     * Gets query for [[TvShowMoods]].
     *
     * @return \yii\db\ActiveQuery
     *
    public function getTvShowMoods()
    {
        return $this->hasMany(TvShowMood::className(), ['mood_id' => 'id']);
    }
    //*/
}
