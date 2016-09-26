<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela justificativa_ajustes
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_justificativa Chave primária da classe JustificativaAjuste
 * @property string $texto_justificativa Texto de justificativa do pedido de ajuste
 * @property char $tipo_justificativa Indicador de tipo de justificativa
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class JustificativaAjuste extends CActiveRecord
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
		return 'justificativa_ajuste';
	}

    /**
     * Método do Yii Framework para definição da chave primária do objeto
     * 
     * A string retornada indica a coluna contendo o identificador único do 
     * objetos.
     * 
     * @return string Nome da coluna referente à chave primária do objeto
     */
    public function primaryKey()
    {
        return 'nr_justificativa';
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
			array('nr_justificativa, texto_justificativa', 'required'),
			array('nr_justificativa', 'length', 'max'=>2),
			array('texto_justificativa', 'length', 'max'=>256),
			array('nr_justificativa, texto_justificativa', 'safe', 'on'=>'search'),
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
            'Ajustes' => array(self::HAS_MANY, 'Ajuste', 'nr_justificativa'),
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
			'nr_justificativa' => 'Nr Seq',
			'texto_justificativa' => 'Denominação',
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

		$criteria->compare('nr_justificativa',$this->nr_justificativa,true);
		$criteria->compare('texto_justificativa',$this->texto_justificativa,true);

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
     * @return JustificativaAjuste A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
