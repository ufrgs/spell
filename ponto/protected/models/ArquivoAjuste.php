<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela arquivo_ajuste
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_arquivo_ajuste Chave primária da classe ArquivoAjuste
 * @property int $nr_ajuste Chave primária da classe Ajuste
 * @property int $nr_abono Chave primária da classe Abono
 * @property string $cod_repositorio Identificador do arquivo
 * @property string $descricao_arquivo Nome do arquivo salvo
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class ArquivoAjuste extends CActiveRecord
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
		return 'arquivo_ajuste';
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
        return 'nr_arquivo_ajuste';
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
			array('cod_repositorio', 'required'),
			array('nr_arquivo_ajuste, nr_ajuste, nr_abono, cod_repositorio', 'length', 'max'=>12),
			array('descricao_arquivo', 'length', 'max'=>256),
			array('nr_arquivo_ajuste, nr_ajuste, nr_abono, cod_repositorio, descricao_arquivo', 'safe', 'on'=>'search'),
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
			'nr_arquivo_ajuste' => 'Nr Seq Arquivo Ajuste',
			'nr_ajuste' => 'Nr Seq Ajuste',
			'nr_abono' => 'Nr Seq Abono',
			'cod_repositorio' => 'Indentificador Repositorio',
			'descricao_arquivo' => 'Descricao Arquivo',
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
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return ArquivoAjuste A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
