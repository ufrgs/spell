<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela log_erro_acesso_registro
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_log Chave primária da classe LogErroAcessoRegistro
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property DateTime $data_log Data em que o log foi gerado
 * @property string $mensagem_log Mensagem do log
 * @property string $ip_log Endereço de IP do usuário
 * @property string $host_log Erro do host
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class LogErroAcessoRegistro extends CActiveRecord
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
		return 'log_erro_acesso_registro';
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
			array('id_pessoa, matricula, nr_vinculo, data_log, mensagem_log, ip_log, host_log', 'required'),
			array('id_pessoa', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo', 'length', 'max'=>1),
			array('mensagem_log', 'length', 'max'=>512),
			array('ip_log', 'length', 'max'=>39),
			array('host_log', 'length', 'max'=>100),
			array('nr_log, id_pessoa, matricula, nr_vinculo, data_log, mensagem_log, ip_log, host_log', 'safe', 'on'=>'search'),
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
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return LogErroAcessoRegistro A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
