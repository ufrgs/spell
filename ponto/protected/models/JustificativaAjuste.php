<?php
/**
 * This is the model class for table "justificativa_ajuste".
 *
 * The followings are the available columns in table 'justificativa_ajuste':
 * @property string $nr_justificativa
 * @property string $texto_justificativa
 */
class JustificativaAjuste extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'justificativa_ajuste';
	}

    public function primaryKey()
    {
        return 'nr_justificativa';
    }
    
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nr_justificativa, texto_justificativa', 'required'),
			array('nr_justificativa', 'length', 'max'=>2),
			array('texto_justificativa', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_justificativa, texto_justificativa', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'Ajustes' => array(self::HAS_MANY, 'Ajuste', 'nr_justificativa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nr_justificativa' => 'Nr Seq',
			'texto_justificativa' => 'Denominação',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nr_justificativa',$this->nr_justificativa,true);
		$criteria->compare('texto_justificativa',$this->texto_justificativa,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArquivoAjuste the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
