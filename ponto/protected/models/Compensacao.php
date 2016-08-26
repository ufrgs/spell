<?php

/**
 * This is the model class for table "compensacao".
 *
 * The followings are the available columns in table 'compensacao':
 * @property string $nr_compensacao
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $periodo_compensacao
 * @property string $data_compensacao
 * @property string $descricao_compensacao
 * @property string $justificativa
 * @property string $id_pessoa_registro
 * @property string $data_hora_registro
 * @property string $ip_registro
 * @property string $id_pessoa_certificacao
 * @property string $data_hora_certificacao
 * @property string $indicador_certificado
 * @property string $justificativa_certificacao
 */
class Compensacao extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'compensacao';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, matricula, nr_vinculo, periodo_compensacao, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
			array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo, indicador_certificado, indicador_excluido', 'length', 'max'=>1),
			array('periodo_compensacao', 'length', 'max'=>5),
			array('descricao_compensacao, justificativa, justificativa_certificacao', 'length', 'max'=>512),
			array('ip_registro', 'length', 'max'=>39),
			array('data_compensacao, data_hora_certificacao', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_compensacao, id_pessoa, matricula, nr_vinculo, periodo_compensacao, data_compensacao, descricao_compensacao, justificativa, id_pessoa_registro, data_hora_registro, ip_registro, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado, justificativa_certificacao', 'safe', 'on'=>'search'),
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
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nr_compensacao' => 'Nr Seq Compensacao',
			'id_pessoa' => 'Cod Pessoa',
			'matricula' => 'Matricula Servidor',
			'nr_vinculo' => 'Nr Vinculo',
			'periodo_compensacao' => 'Periodo Compensacao',
			'data_compensacao' => 'Data Compensacao',
			'descricao_compensacao' => 'Descricao Compensacao',
			'justificativa' => 'justificativa',
			'id_pessoa_registro' => 'Cod Pessoa Registro',
			'data_hora_registro' => 'Data Hora Registro',
			'ip_registro' => 'Ipregistro',
			'id_pessoa_certificacao' => 'Cod Pessoa Certificacao',
			'data_hora_certificacao' => 'Data Hora Certificacao',
			'indicador_certificado' => 'Indicador Certificacao',
			'justificativa_certificacao' => 'justificativa Certificacao',
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

		$criteria->compare('nr_compensacao',$this->nr_compensacao,false);
		$criteria->compare('id_pessoa',$this->id_pessoa,false);
		$criteria->compare('matricula',$this->matricula,false);
		$criteria->compare('nr_vinculo',$this->nr_vinculo,false);
		$criteria->compare('periodo_compensacao',$this->periodo_compensacao,false);
		$criteria->compare('data_compensacao',$this->data_compensacao,false);
		$criteria->compare('descricao_compensacao',$this->descricao_compensacao,false);
		$criteria->compare('justificativa',$this->justificativa,false);
		$criteria->compare('id_pessoa_registro',$this->id_pessoa_registro,false);
		$criteria->compare('data_hora_registro',$this->data_hora_registro,false);
		$criteria->compare('ip_registro',$this->ip_registro,false);
		$criteria->compare('id_pessoa_certificacao',$this->id_pessoa_certificacao,false);
		$criteria->compare('data_hora_certificacao',$this->data_hora_certificacao,false);
		$criteria->compare('indicador_certificado',$this->indicador_certificado,false);
		$criteria->compare('justificativa_certificacao',$this->justificativa_certificacao,false);
        $criteria->addCondition("coalesce(indicador_excluido, 'N') = 'N'");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 15,
            ),
            'sort' => array(
                'attributes' => array(
                    '*',
                ),
                'defaultOrder' => array(
                    'data_hora_registro' => CSort::SORT_DESC,
                ),
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Compensacao the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Retorna os dias com compensacao, os dias que estao com pedidos de compensacao pendente e o total de minutos compensado
     * @param int $id_pessoa
     * @param int $nr_vinculo
     * @param int $mes
     * @param int $ano
     * @return array(array diasComCompensacao, array diasComCompensacaoPendente, int totalCompensacao)
     */
    public static function getCompensacoesMes($id_pessoa, $nr_vinculo, $mes, $ano)
    {
        $compensacoes = Compensacao::model()->findAll(array(
            'condition' => "id_pessoa = :id_pessoa 
                            and nr_vinculo = :nr_vinculo
                            and month(data_compensacao) = :mes
                            and year(data_compensacao) = :ano
                            and coalesce(indicador_certificado, 'S') = 'S'
                            and coalesce(indicador_excluido, 'N') = 'N'",
            'params' => array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $nr_vinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
            ),
            'order' => 'data_compensacao'
        ));

        $diasComCompensacao = array();
        $diasComCompensacaoPendente = array();
        $totalCompensacao = 0;
        foreach ($compensacoes as $compensacao) {
            if (!isset($diasComCompensacao[ date("d/m/Y", strtotime($compensacao['data_compensacao'])) ])) {
                $diasComCompensacao[ date("d/m/Y", strtotime($compensacao['data_compensacao'])) ] = 0;
                $diasComCompensacaoPendente[ date("d/m/Y", strtotime($compensacao['data_compensacao'])) ] = false;
            }
            $diasComCompensacao[ date("d/m/Y", strtotime($compensacao['data_compensacao'])) ] += $compensacao['periodo_compensacao'];
            $totalCompensacao += $compensacao['periodo_compensacao'];
            if (trim($compensacao['data_hora_certificacao']) == '') {
                $diasComCompensacaoPendente[ date("d/m/Y", strtotime($compensacao['data_compensacao'])) ] = true;
            }
        }
        
        return array(
            'diasComCompensacao' => $diasComCompensacao,
            'diasComCompensacaoPendente' => $diasComCompensacaoPendente,
            'totalCompensacao' => $totalCompensacao,
        );
    }
    
    public static function getCargaHorariaCompensadaMes($id_pessoa, $nr_vinculo, $mes, $ano, $consideraPedidoEmAnalise = true)
    {
        $command = Yii::app()->db->cache(10)->createCommand(); // cache de 10 segundos
        $compensacao = $command
                    ->select('sum(periodo_compensacao)')
                    ->from('compensacao')
                    ->where("
                        id_pessoa = :id_pessoa 
                        and nr_vinculo = :nr_vinculo 
                        and coalesce(indicador_certificado, :consideraPedidoEmAnalise) = 'S' -- considera um pedido em analise na contagem
                        and month(data_compensacao) = :mes
                        and year(data_compensacao) = :ano
                        and coalesce(indicador_excluido, 'N') = 'N'
                    ", array(
                        ':id_pessoa' => $id_pessoa,
                        ':nr_vinculo' => $nr_vinculo,
                        ':consideraPedidoEmAnalise' => ($consideraPedidoEmAnalise ? 'S' : 'N'),
                        ':mes' => $mes,
                        ':ano' => $ano,
                    ))->queryScalar();
        
        return $compensacao;
    }
}
