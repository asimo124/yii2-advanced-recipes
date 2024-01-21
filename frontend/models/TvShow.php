<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%tv_show}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $image_path
 * @property int $service_id
 * @property int $show_type_id
 *
 * @property TvService $service
 * @property TvShowType $showType
 * @property TvShowMood[] $tvShowMoods
 */
class TvShow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tv_show}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'service_id', 'show_type_id'], 'required'],
            [['service_id', 'show_type_id'], 'integer'],
            [['title', 'image_path'], 'string', 'max' => 255],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => TvService::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['show_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TvShowType::className(), 'targetAttribute' => ['show_type_id' => 'id']],
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
            'image_path' => 'Image Path',
            'service_id' => 'Service ID',
            'show_type_id' => 'Show Type ID',
        ];
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(TvService::className(), ['id' => 'service_id']);
    }

    /**
     * Gets query for [[ShowType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShowType()
    {
        return $this->hasOne(TvShowType::className(), ['id' => 'show_type_id']);
    }

    /**
     * Gets query for [[TvShowMoods]].
     *
     * @return \yii\db\ActiveQuery
     *
    public function getTvShowMoods()
    {
        return $this->hasMany(TvShowMood::className(), ['show_id' => 'id']);
    }
    //*/
}
