<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%tv_show_mood}}".
 *
 * @property int $id
 * @property int $show_id
 * @property int $mood_id
 *
 * @property TvMood $mood
 * @property TvShow $show
 */
class TvShowMood extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tv_show_mood}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['show_id', 'mood_id'], 'required'],
            [['show_id', 'mood_id'], 'integer'],
            [['mood_id'], 'exist', 'skipOnError' => true, 'targetClass' => TvMood::className(), 'targetAttribute' => ['mood_id' => 'id']],
            [['show_id'], 'exist', 'skipOnError' => true, 'targetClass' => TvShow::className(), 'targetAttribute' => ['show_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'show_id' => 'Show ID',
            'mood_id' => 'Mood ID',
        ];
    }

    /**
     * Gets query for [[Mood]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMood()
    {
        return $this->hasOne(TvMood::className(), ['id' => 'mood_id']);
    }

    /**
     * Gets query for [[Show]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShow()
    {
        return $this->hasOne(TvShow::className(), ['id' => 'show_id']);
    }
}
