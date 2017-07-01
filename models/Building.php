<?php

namespace app\models;

/**
 * This is the model class for table "building".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $avatar
 * @property string $topText
 * @property string $bottomText
 * @property integer $enabled
 *
 * @property Meter[] $meters
 */
class Building extends \yii\db\ActiveRecord
{
	public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['enabled'], 'integer'],
            [['name', 'url', 'avatar'], 'string', 'max' => 50],
            [['topText', 'bottomText'], 'string', 'max' => 128],
            [['image'],  'file', 'extensions' => 'png, jpg, gif',   'skipOnEmpty' => true],
            [['url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'avatar' => 'Avatar',
            'topText' => 'Top Text',
            'bottomText' => 'Bottom Text',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeters()
    {
        return $this->hasMany(Meter::className(), ['building_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BuildingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BuildingQuery(get_called_class());
    }
}
