<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela compensacao
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_compensacao Chave primária da classe Compensacao
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property int $periodo_compensacao Período de tempo em que a compensação foi registrada
 * @property DateTime $data_compensacao Data em que a compensação foi registrada
 * @property string $descricao_compensacao Descrição da compensação
 * @property string $justificativa Motivo do pedido de comepnsação
 * @property int $id_pessoa_registro Código da pessoa que registrou o pedido
 * @property DateTime $data_hora_registro Data e hora em que o pedido foi registrado
 * @property string $ip_registro Enderço de IP do usuário
 * @property int $id_pessoa_certificacao Código da pessoa que certificou o pedido. Chave primária da classe Pessoa
 * @property DateTime $data_hora_certificacao Data e hora em que o pedido foi certificado. Chave primária da classe Pessoa
 * @property char $indicador_certificado Indicador para o estado de certificação do documento (S ou N)
 * @property string $justificativa_certificacao Texto de justificativa do certificador para o abono
 * @property char $indicador_excluido Indicador para o estado do ajuste (S ou N)
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Compensacao extends CActiveRecord
{
	/**
     * Método do Yii Framework para definição da tabela associada ao objeto
     * 
     * A string retornada define para o Yii qual tabela contém os registros a
     * serem mapeados para essa classe.
     * 
     * @return string Nome da tabela no banco de dados associada ao objeto
     */
	public function tableName()
	{
		return 'compensacao';
	}

	/**
     * Método do Yii Framework para definição de regras de validação
     * 
     * Aqui são definidos os atributos das colunas da tabela que presenta o 
     * objeto como os campos que aceitam valores nulos e tamanho máximo de 
     * caracteres suportados.
     * 
     * É recomendado apenas definir as regras para os atributos que forem ser 
     * utilizados com dados do usuário.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/form.model#declaring-validation-rules Como declarar regras
     * @return array Regras de validação para este modelo
     */
	public function rules()
	{
		return array(
			array('id_pessoa, matricula, nr_vinculo, periodo_compensacao, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
			array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo, indicador_certificado, indicador_excluido', 'length', 'max'=>1),
			array('periodo_compensacao', 'length', 'max'=>5),
			array('descricao_compensacao, justificativa, justificativa_certificacao', 'length', 'max'=>512),
			array('ip_registro', 'length', 'max'=>39),
			array('data_compensacao, data_hora_certificacao', 'safe'),
			array('nr_compensacao, id_pessoa, matricula, nr_vinculo, periodo_compensacao, data_compensacao, descricao_compensacao, justificativa, id_pessoa_registro, data_hora_registro, ip_registro, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado, justificativa_certificacao', 'safe', 'on'=>'search'),
		);
	}

	/**
     * Método do Yii Framework para definição de relacionamentos entre tabelas
     * 
     * Aqui são definidos as tabelas, os tipos de relação e as colunas que as 
     * possuem.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/database.arr#declaring-relationship Como declarar relacionamentos
     * @return array Relacionamentos que esta tabela possui
     */
	public function relations()
	{
		return array(
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
		);
	}

	/**
     * Método do Yii Framework para definir descrições às colunas da tabela
     * 
     * Aqui são definidos nomes mais amigáveis aos atributos do objeto. É 
     * utilizado para gerar mensagens de erros mais claras e mostrar dados nas
     * telas da aplicação.
     * 
     * @return array Lista de descrições no formato 'coluna'=>'descrição'
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
     * Método do Yii Framework para buscar modelos
     *
     * Aqui é feita a pesquisa de um modelo de acordo com determinadas condições
     * passadas por parâmetro.
     * 
     * @return CActiveDataProvider Conjunto de dados retornados da consulta
     */
	public function search()
	{
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
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return Compensacao A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Método para busca de compensações em um determinado período
     * 
     * A partir dos dados recebidos por parâmetro realiza uma busca por 
     * compensações de acordo com o período, vínculo e pessoa informados.
     * 
     * O método retorna um array contendo as seguintes chaves: 
     * diasComCompensação, diasComCompensacaoPendente e totalCompensacao.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $nr_vinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @return array Retorna um array contendo as compensações encontrados
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
    
    /**
     * Método para busca de compensações em um determinado período
     * 
     * A partir dos dados recebidos por parâmetro realiza uma busca pela carga
     * horária compensada de acordo com o período, vínculo e pessoa informados.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $nr_vinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @param boolean $consideraPedidoEmAnalise Indicador utilizado para considerar os pedidos ainda não certificados
     * @return array Retorna um array contendo as compensações encontrados
     */
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
