<?php

/**
 * This is the model class for table "definicoes_orgao".
 *
 * The followings are the available columns in table 'definicoes_orgao':
 * @property string $id_orgao
 * @property string $hora_inicio_expediente
 * @property string $hora_inicio_expediente_sab
 * @property string $hora_inicio_expediente_dom
 * @property string $hora_fim_expediente
 * @property string $permite_ocorrencia
 * @property string $id_pessoa_atualizacao
 * @property string $data_atualizacao
 */
class DefinicoesOrgao extends CActiveRecord
{
    public $HoraInicioExpediente_hora = null;
    public $HoraInicioExpedienteSabado_hora = null;
    public $HoraInicioExpedienteDomingo_hora = null;
    public $HoraFimExpediente_hora = null;
    public $HoraFimExpedienteSabado_hora = null;
    public $HoraFimExpedienteDomingo_hora = null;
    public $sabado = null;
    public $domingo = null;
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'definicoes_orgao';
	}

        public function behaviors() {
            return array(
                'HoraInicioBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente',
                    'atributoData' => false
                ),
                'HoraInicioSabadoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente_sab',
                    'atributoData' => false
                ),
                'HoraInicioDomingoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente_dom',
                    'atributoData' => false
                ),
                'HoraFimBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente',
                    'atributoData' => false
                ),
                'HoraFimSabadoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente_sab',
                    'atributoData' => false
                ),
                'HoraFimDomingoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente_dom',
                    'atributoData' => false
                ),
            );            
        }
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_orgao, id_pessoa_atualizacao, data_atualizacao', 'required'),
			array('id_orgao', 'length', 'max'=>5),
			array('permite_ocorrencia', 'length', 'max'=>1),
			array('id_pessoa_atualizacao', 'length', 'max'=>6),
			array('hora_inicio_expediente, hora_inicio_expediente_sab, '
                            . 'hora_inicio_expediente_dom, hora_fim_expediente, '
                            . 'hora_fim_expediente_sab, hora_fim_expediente_dom', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_orgao, hora_inicio_expediente, hora_inicio_expediente_sab, '
                            . 'hora_inicio_expediente_dom, hora_fim_expediente, hora_fim_expediente_sab, '
                            . 'hora_fim_expediente_dom, permite_ocorrencia, id_pessoa_atualizacao, '
                            . 'data_atualizacao', 'safe', 'on'=>'search'),
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
            'Orgao' => array(self::BELONGS_TO, 'Orgao', 'id_orgao'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_orgao' => 'Cod Orgao',
			'hora_inicio_expediente' => 'Hora Inicio Expediente',
                        'hora_inicio_expediente_sab' => 'Hora Inicio Expediente Sábado',
                        'hora_inicio_expediente_dom' => 'Hora Inicio Expediente Domingo',
			'hora_fim_expediente' => 'Hora Fim Expediente',
                        'hora_fim_expediente_sab' => 'Hora Fim Expediente Sábado',
                        'hora_fim_expediente_dom' => 'Hora Fim Expediente Domingo',
			'permite_ocorrencia' => 'Indicador Permite Ocorrencia',
			'id_pessoa_atualizacao' => 'Cod Pessoa Ultima Atu',
			'data_atualizacao' => 'Data Hora Ultima Atu',
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

		$criteria->compare('id_orgao',$this->id_orgao,false);
		$criteria->compare('hora_inicio_expediente',$this->hora_inicio_expediente,false);
        $criteria->compare('hora_inicio_expediente_sab',$this->hora_inicio_expediente_sab,false);
        $criteria->compare('hora_inicio_expediente_dom',$this->hora_inicio_expediente_dom,false);
		$criteria->compare('hora_fim_expediente',$this->hora_fim_expediente,false);
        $criteria->compare('hora_fim_expediente_sab',$this->hora_fim_expediente_sab,false);
        $criteria->compare('hora_fim_expediente_dom',$this->hora_fim_expediente_dom,false);
		$criteria->compare('permite_ocorrencia',$this->permite_ocorrencia,false);
		$criteria->compare('id_pessoa_atualizacao',$this->id_pessoa_atualizacao,false);
		$criteria->compare('data_atualizacao',$this->data_atualizacao,false);

        $criteria->with = array(
            'Orgao' => array('select' => 'nome_orgao, sigla_orgao, matricula_dirigente, matricula_substituto'),
        );
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DefinicoesOrgao the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function afterFind() {
            
            if (isset($this->hora_inicio_expediente_sab) && 
                    !is_null($this->hora_inicio_expediente_sab)){
                $this->sabado = true;
            }
            
            else {
                $this->sabado = false;
            }
            
            if (isset($this->hora_inicio_expediente_dom) &&
                    !is_null($this->hora_inicio_expediente_dom)){
                $this->domingo = true;
            }
            
            else {
                $this->domingo = false;
            }
            
            parent::afterFind();
        }
}
