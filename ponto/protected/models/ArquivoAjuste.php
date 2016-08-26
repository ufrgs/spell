<?php

/**
 * This is the model class for table "arquivo_ajuste".
 *
 * The followings are the available columns in table 'arquivo_ajuste':
 * @property string $nr_arquivo_ajuste
 * @property string $nr_ajuste
 * @property string $cod_repositorio
 * @property string $descricao_arquivo
 */
class ArquivoAjuste extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'arquivo_ajuste';
	}

    public function primaryKey()
    {
        return 'nr_arquivo_ajuste';
    }
    
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_repositorio', 'required'),
			array('nr_arquivo_ajuste, nr_ajuste, nr_abono, cod_repositorio', 'length', 'max'=>12),
			array('descricao_arquivo', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_arquivo_ajuste, nr_ajuste, nr_abono, cod_repositorio, descricao_arquivo', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nr_arquivo_ajuste' => 'Nr Seq Arquivo Ajuste',
			'nr_ajuste' => 'Nr Seq Ajuste',
			'nr_abono' => 'Nr Seq Abono',
			'cod_repositorio' => 'Indentificador Repositorio',
			'descricao_arquivo' => 'Descricao Arquivo',
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

		$criteria->compare('nr_arquivo_ajuste',$this->nr_arquivo_ajuste,true);
		$criteria->compare('nr_ajuste',$this->nr_ajuste,true);
		$criteria->compare('nr_abono',$this->nr_abono,true);
		$criteria->compare('cod_repositorio',$this->cod_repositorio,true);
		$criteria->compare('descricao_arquivo',$this->descricao_arquivo,true);

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
