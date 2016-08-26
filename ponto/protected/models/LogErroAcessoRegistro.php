<?php

/**
 * This is the model class for table "log_erro_acesso_registro".
 *
 * The followings are the available columns in table 'log_erro_acesso_registro':
 * @property string $nr_log
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $data_log
 * @property string $mensagem_log
 * @property string $ip_log
 * @property string $host_log
 */
class LogErroAcessoRegistro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log_erro_acesso_registro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, matricula, nr_vinculo, data_log, mensagem_log, ip_log, host_log', 'required'),
			array('id_pessoa', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo', 'length', 'max'=>1),
			array('mensagem_log', 'length', 'max'=>512),
			array('ip_log', 'length', 'max'=>39),
			array('host_log', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_log, id_pessoa, matricula, nr_vinculo, data_log, mensagem_log, ip_log, host_log', 'safe', 'on'=>'search'),
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
			'nr_log' => 'Nr Seq Log',
			'id_pessoa' => 'Cod Pessoa',
			'matricula' => 'Matricula Servidor',
			'nr_vinculo' => 'Nr Vinculo',
			'data_log' => 'Data Hora Log',
			'mensagem_log' => 'Mensagem Erro',
			'ip_log' => 'Iperro',
			'host_log' => 'Host Erro',
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

		$criteria->compare('nr_log',$this->nr_log,false);
		$criteria->compare('id_pessoa',$this->id_pessoa,false);
		$criteria->compare('matricula',$this->matricula,false);
		$criteria->compare('nr_vinculo',$this->nr_vinculo,false);
		$criteria->compare('data_log',$this->data_log,false);
		$criteria->compare('mensagem_log',$this->mensagem_log,false);
		$criteria->compare('ip_log',$this->ip_log,false);
		$criteria->compare('host_log',$this->host_log,false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogErroAcessoRegistro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
