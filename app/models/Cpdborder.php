<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cpdborder".
 *
 * @property int $id
 * @property int $panel_id
 * @property string $machineid
 * @property string $panellot
 * @property string $datetime
 *
 * @property Cpdbcalc[] $cpdbcalcs
 * @property Cpdbkb[] $cpdbkbs
 * @property Item[] $items
 * @property Panel $panel
 * @property Testresult[] $testresults
 */
class Cpdborder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cpdborder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['panel_id'], 'required'],
            [['panel_id'], 'integer'],
            [['datetime'], 'safe'],
            [['machineid'], 'string', 'max' => 6],
            [['panellot'], 'string', 'max' => 45],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'panel_id' => 'Panel ID',
            'machineid' => 'Machineid',
            'panellot' => 'Panellot',
            'datetime' => 'Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdbcalcs()
    {
        return $this->hasMany(Cpdbcalc::className(), ['cpdborder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdbkbs()
    {
        return $this->hasMany(Cpdbkb::className(), ['cpdborder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])->viaTable('cpdbkb', ['cpdborder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPanel()
    {
        return $this->hasOne(Panel::className(), ['id' => 'panel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestresults()
    {
        return $this->hasMany(Testresult::className(), ['cpdborder_id' => 'id']);
    }
}
