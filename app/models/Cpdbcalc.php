<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cpdbcalc".
 *
 * @property int $item_id
 * @property int $cpdborder_id
 * @property int $calibrator_id
 * @property double $target
 * @property double $xvalue
 * @property double $bvalue
 * @property int $xcount
 *
 * @property Calibrator $calibrator
 * @property Cpdborder $cpdborder
 * @property Item $item
 */
class Cpdbcalc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cpdbcalc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'cpdborder_id', 'calibrator_id'], 'required'],
            [['item_id', 'cpdborder_id', 'calibrator_id'], 'integer'],
            [['target', 'xvalue', 'bvalue'], 'number'],
            [['xcount'], 'string', 'max' => 4],
            [['item_id', 'cpdborder_id', 'calibrator_id'], 'unique', 'targetAttribute' => ['item_id', 'cpdborder_id', 'calibrator_id']],
            [['calibrator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Calibrator::className(), 'targetAttribute' => ['calibrator_id' => 'id']],
            [['cpdborder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cpdborder::className(), 'targetAttribute' => ['cpdborder_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'cpdborder_id' => 'Cpdborder ID',
            'calibrator_id' => 'Calibrator ID',
            'target' => 'Target',
            'xvalue' => 'Xvalue',
            'bvalue' => 'Bvalue',
            'xcount' => 'Xcount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalibrator()
    {
        return $this->hasOne(Calibrator::className(), ['id' => 'calibrator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdborder()
    {
        return $this->hasOne(Cpdborder::className(), ['id' => 'cpdborder_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
