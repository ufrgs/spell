<?php

/**
 * This is the model class for table "PONTOEAJUSTE".
 *
 * The followings are the available columns in table 'PONTOEAJUSTE':
 * @property string $nr_seq
 * @property string $tipo
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $data_hora_ponto
 * @property string $entrada_saida
 * @property string $id_pessoa_registro
 * @property string $data_hora_registro
 * @property string $ip_registro
 * @property string $justificativa
 * @property string $id_pessoa_certificacao
 * @property string $data_hora_certificacao
 * @property string $indicador_certificado
 */
class PontoEAjuste extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'v_ponto_e_ajuste';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nr_seq, id_pessoa, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
			array('nr_seq', 'length', 'max'=>12),
			array('tipo', 'length', 'max'=>1),
			array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo, entrada_saida, indicador_certificado', 'length', 'max'=>1),
			array('ip_registro', 'length', 'max'=>39),
			array('justificativa', 'length', 'max'=>2048),
			array('data_hora_certificacao', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_seq, tipo, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on'=>'search'),
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
			'nr_seq' => 'Nr Seq Registro',
			'tipo' => 'tipo',
			'id_pessoa' => 'Cod Pessoa',
			'matricula' => 'Matricula Servidor',
			'nr_vinculo' => 'Nr Vinculo',
			'data_hora_ponto' => 'Data Hora Ponto',
			'entrada_saida' => 'Indicador Entrada Saida',
			'id_pessoa_registro' => 'Cod Pessoa Registro',
			'data_hora_registro' => 'Data Hora Registro',
			'ip_registro' => 'Ipregistro',
			'justificativa' => 'justificativa',
			'id_pessoa_certificacao' => 'Cod Pessoa Certificacao',
			'data_hora_certificacao' => 'Data Hora Certificacao',
			'indicador_certificado' => 'Indicador Certificacao',
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

		$criteria->compare('nr_seq',$this->nr_seq,true);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('id_pessoa',$this->id_pessoa,true);
		$criteria->compare('matricula',$this->matricula,true);
		$criteria->compare('nr_vinculo',$this->nr_vinculo,true);
		$criteria->compare('data_hora_ponto',$this->data_hora_ponto,true);
		$criteria->compare('entrada_saida',$this->entrada_saida,true);
		$criteria->compare('id_pessoa_registro',$this->id_pessoa_registro,true);
		$criteria->compare('data_hora_registro',$this->data_hora_registro,true);
		$criteria->compare('ip_registro',$this->ip_registro,true);
		$criteria->compare('justificativa',$this->justificativa,true);
		$criteria->compare('id_pessoa_certificacao',$this->id_pessoa_certificacao,true);
		$criteria->compare('data_hora_certificacao',$this->data_hora_certificacao,true);
		$criteria->compare('indicador_certificado',$this->indicador_certificado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ajuste the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public static function getRegistrosMes($id_pessoa, $nr_vinculo, $mes, $ano, $consolidado = false)
    {
        $restricaoAjustes = "and coalesce(indicador_certificado, 'S') = 'S'
                            and (tipo = 'A'
                                or (tipo = 'R' and not exists (
                                        select 1 from ajuste A
                                        where A.nr_ponto = nr_seq
                                    )
                            )) ";
        if ($consolidado) {
            // se e para buscar o consolidado, so interessam ajustes que foram certificados
            $restricaoAjustes = "and (
                                    (tipo = 'A' and indicador_certificado = 'S')
                                    or (tipo = 'R' and not exists (
                                            select 1 from ajuste A
                                            where A.nr_ponto = nr_seq
                                                and A.indicador_certificado = 'S'
                                        )
                                ))";
        }
        $registros = PontoEAjuste::model()->findAll(array(
            'condition' => "id_pessoa = :id_pessoa 
                            and nr_vinculo = :nr_vinculo
                            and month(data_hora_ponto) = :mes
                            and year(data_hora_ponto) = :ano
                            $restricaoAjustes ", 
            'params' => array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $nr_vinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
            ),
            'order' => 'data_hora_ponto'
        ));
        
        return $registros;
    }
}
