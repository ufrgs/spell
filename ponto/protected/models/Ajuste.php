<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela ajustes
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_ajuste Chave primária da classe Ajuste
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property DateTime $data_hora_ponto Data no formato brasileiro
 * @property char $entrada_saida Indicador de tipo de registro (E ou S)
 * @property int $id_pessoa_registro Guarda a chave primária da classe Pessoa
 * @property DateTime $data_hora_registro Data atual no formato AAAA-MM-DD HH:MM:SS
 * @property string $ip_registro Endereço de IP do usuário
 * @property string $justificativa Texto de justificativa do ajuste
 * @property int $id_pessoa_certificacao Chave primária da classe Pessoa contendo o código do certificador
 * @property DateTime $data_hora_certificacao Data em que o ajuste foi certificado
 * @property char $indicador_certificado Indicador para o estado de certificação do documento (S ou N)
 * @property int $nr_ponto Chave primária da classe Ponto
 * @property int $nr_justificativa Chave primária da classe JustificativaAjuste
 * @property string $justificativa_certificacao Texto de justificativa do certificador para o ajuste
 * @property char $indicador_excluido Indicador para o estado do ajuste (S ou N)
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Ajuste extends CActiveRecord
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
        return 'ajuste';
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
        return 'nr_ajuste';
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
            array('id_pessoa, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
            array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, entrada_saida, indicador_certificado, indicador_excluido', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            array('justificativa', 'length', 'max' => 2048),
            array('justificativa_certificacao', 'length', 'max' => 512),
            array('data_hora_certificacao', 'safe'),

            // Colunas que podem ser utilizadas pelo método search()
            array('nr_ajuste, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on' => 'search'),
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
            'Ponto' => array(self::BELONGS_TO, 'Ponto', 'nr_ponto'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
            'JustificativaAjuste' => array(self::BELONGS_TO, 'JustificativaAjuste', 'nr_justificativa'),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
            'Arquivos' => array(self::HAS_MANY, 'ArquivoAjuste', 'nr_ajuste'),
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
            'nr_ajuste' => 'Nr Seq Ajuste',
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
     * Método do Yii Framework para buscar modelos
     *
     * Aqui é feita a pesquisa de um modelo de acordo com determinadas condições
     * passadas por parâmetro.
     * 
     * @param array $dataProviderOptions Filtro a ser usado na consulta
     * @return CActiveDataProvider Conjunto de dados retornados da consulta
     */
    public function search($dataProviderOptions = array())
    {
        $criteria = new CDbCriteria;

        $criteria->with = array(
                'Pessoa' => array(
                    'select' => 'nome_pessoa'
                ),
                'Certificador' => array(
                    'select' => 'nome_pessoa'
                ),
                'DadoFuncional' => array(
                    'select' => 'regime_trabalho',
                ),
                'DadoFuncional.CatFuncional' => array(
                    'select' => 'nome_categoria'
                ),
                'DadoFuncional.OrgaoExercicio' => array(
                    'select' => 'sigla_orgao, nome_orgao'
                ), 'JustificativaAjuste', 'Arquivos', 'Certificador');
        $criteria->compare('t.nr_ajuste', $this->nr_ajuste, false);
        $criteria->compare('t.id_pessoa', $this->id_pessoa, false);
        $criteria->compare('t.matricula', $this->matricula, false);
        $criteria->compare('t.nr_vinculo', $this->nr_vinculo, false);
        $criteria->compare('t.indicador_certificado', $this->indicador_certificado, false);
        $criteria->compare('t.id_pessoa_certificacao', $this->id_pessoa_certificacao, false);
        $criteria->addCondition("coalesce(t.indicador_excluido, 'N') = 'N'");

        return new CActiveDataProvider($this, array_merge(array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 15,
            ),
            'sort' => array(
                'attributes' => array(
                    'nr_ajuste',
                    'id_pessoa',
                    'matricula',
                    'data_hora_ponto',
                    'entrada_saida',
                    'id_pessoa_registro',
                    'data_hora_registro',
                    'ip_registro',
                    'id_pessoa_certificacao',
                    'justificativa',
                    'data_hora_certificacao',
                    'indicador_certificado',
                    'justificativa_certificacao',
                    'nr_ponto',
                    'Pessoa.nome_pessoa' => array(
                        'asc' => 'Pessoa.nome_pessoa asc',
                        SORT_ASC => 'Pessoa.nome_pessoa asc',
                        'desc' => 'Pessoa.nome_pessoa desc',
                        SORT_DESC => 'Pessoa.nome_pessoa desc',
                    ),
                    'Certificador.nome_pessoa' => array(
                        'asc' => 'Certificador.nome_pessoa asc',
                        SORT_ASC => 'Certificador.nome_pessoa asc',
                        'desc' => 'Certificador.nome_pessoa desc',
                        SORT_DESC => 'Certificador.nome_pessoa desc',
                    ),
                ),
                'defaultOrder' => array(
                    'data_hora_registro' => CSort::SORT_DESC,
                ),
            ),
        ), $dataProviderOptions));
    }

    /**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return Ajuste A classe que é Active Record
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Método para listagem de documentos anexados
     * 
     * Esse método busca todos os documentos anexados em um pedido de ajuste e 
     * devolve o link para download do documento.
     * 
     * @return string Links para acesso ao documento anexado
     */
    public function listaAnexos()
    {
        $listagem = "";
        $repositorio = new Repositorio;
        if (!empty($this->Arquivos)) {
            foreach ($this->Arquivos as $arquivo) {
                //$listagem .= CHtml::link($arquivo->descricao_arquivo, Yii::app()->createUrl("ajuste/anexo", array('arquivo' => $arquivo->nr_arquivo_ajuste)), array('target' => '_blank')).'<br/>';
                $link = $repositorio->devolveLinkExibicao(92, $arquivo->cod_repositorio);
                $listagem .= CHtml::link($arquivo->descricao_arquivo, $link, array('target' => '_blank')).'<br/>';
            }
        }
        else {
            $listagem = 'sem anexos';
        }
        return $listagem;
    }

}