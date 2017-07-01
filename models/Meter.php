<?php

namespace app\models;

/**
 * This is the model class for table "meter".
 *
 * @property integer $id
 * @property integer $building_id
 * @property integer $energy
 * @property integer $pleasantness
 * @property string $date_created
 * @property string $date_meter
 *
 * @property Building $building
 */
class Meter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_id', 'energy', 'pleasantness'], 'required'],
            [['building_id', 'energy', 'pleasantness'], 'integer'],
            [['date_created', 'date_meter'], 'safe'],
            [['building_id'], 'exist', 'skipOnError' => true, 'targetClass' => Building::className(), 'targetAttribute' => ['building_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'building_id' => 'Building ID',
            'energy' => 'Energy',
            'pleasantness' => 'pleasantness',
            'date_created' => 'Date Created',
            'date_meter' => 'Date Meter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }

    /**
     * @inheritdoc
     * @return MeterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MeterQuery(get_called_class());
    }
}
