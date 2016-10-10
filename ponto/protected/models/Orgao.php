<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela orgao
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $id_orgao Chave primária da classe Orgao
 * @property string $sigla_orgao Sigla que representa o órgão
 * @property string $nome_orgao Nome do órgão
 * @property string $email E-mail para contato com órgão
 * @property int $matricula_dirigente Matrícula do digirente do órgão. Chave primária da classe DadoFuncional
 * @property int $matricula_substituto Matrícula da pessoa substituta do dirigente. Chave primária da classe DadoFuncional
 * @property int $id_orgao_superior Chave primária do órgão superior ao atual. Chave primária da classe Orgao
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Orgao extends CActiveRecord
{
    /**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return Orgao A classe que é Active Record
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

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
        return 'Orgao';
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
        return 'id_orgao';
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
            'DirigenteOrgao'  => array(self::BELONGS_TO, 'DadoFuncional', 'matricula_dirigente'),
			'DirigenteSubstituto'  =>array(self::BELONGS_TO, 'DadoFuncional', 'matricula_substituto'),
        );
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
            array('id_orgao, sigla_orgao, nome_orgao', 'required'),
            array('id_orgao', 'length', 'max' => 5),
            array('sigla_orgao', 'length', 'max' => 10),
            array('nome_orgao', 'length', 'max' => 255),
            array('email', 'length', 'max' => 150),
            array('matricula_dirigente, matricula_substituto', 'length', 'max' => 8),
            array('id_orgao_superior', 'length', 'max' => 5)
        );
    }
}
