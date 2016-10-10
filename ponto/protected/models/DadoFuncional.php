<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela dado_funcional
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property char $regime_trabalho Tempo de trabalho dessa categoria (Ex: 40)
 * @property int $id_grupo Chave primária da classe GrupoEmprego
 * @property int $id_categoria Chave primária da classe CategoriaFuncional
 * @property int $orgao_lotacao Chave primária do órgão o qual o funcionário é lotado
 * @property int $orgao_exercicio Chave primária do órgão o qual o funcionário atende
 * @property DateTime $data_ingresso Data em que a pessoa ingressou no cargo
 * @property DateTime $data_desligamento Data em que a pessoa se desligou do cargo
 * @property DateTime $data_aposentadoria Data em que a pessoa se aposentou do cargo
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class DadoFuncional extends CActiveRecord
{

    /**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return DadoFuncional A classe que é Active Record
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
        return 'dado_funcional';
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
        return array('matricula', 'nr_vinculo');
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
            'OrgaoExercicio' => array(self::BELONGS_TO, 'Orgao', 'orgao_exercicio'),
            'OrgaoLotacao' => array(self::BELONGS_TO, 'Orgao', 'orgao_lotacao'),
            'CatFuncional' => array(self::BELONGS_TO, 'CategoriaFuncional', 'id_categoria'),
            'GrupoEmprego' => array(self::BELONGS_TO, 'GrupoEmprego', 'id_grupo'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
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
            array('matricula, nr_vinculo, id_pessoa, regime_trabalho, id_grupo, id_categoria, orgao_lotacao, orgao_exercicio, data_ingresso', 'required'),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo', 'length', 'max' => 1),
            array('id_pessoa', 'length', 'max' => 6),
            array('regime_trabalho', 'length', 'max' => 2),
            array('id_grupo', 'length', 'max' => 2),
            array('id_categoria', 'length', 'max' => 3),
            array('orgao_lotacao, orgao_exercicio', 'length', 'max' => 5)
        );
    }
}
