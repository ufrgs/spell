<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela abono
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_abono Chave primária da classe Abono
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property DateTime $data_abono Data do abono registrado
 * @property int $periodo_abono Hora do abono registrado
 * @property string $justificativa Texto de justificativa do ajuste
 * @property int $id_pessoa_certificacao Chave primária da classe Pessoa contendo o código do certificador
 * @property DateTime $data_hora_certificacao Data em que o ajuste foi certificado
 * @property char $indicador_certificado Indicador para o estado de certificação do documento (S ou N)
 * @property int $id_pessoa_registro Guarda a chave primária da classe Pessoa
 * @property DateTime $data_hora_registro Data atual no formato AAAA-MM-DD HH:MM:SS
 * @property string $ip_registro Endereço de IP do usuário
 * @property string $justificativa_certificacao Texto de justificativa do certificador para o ajuste
 * @property int $nr_justificativa Chave primária da classe JustificativaAjuste
 * @property char $indicador_excluido Indicador para o estado do ajuste (S ou N)
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Abono extends CActiveRecord
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
        return 'abono';
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
        return 'nr_abono';
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
            array('id_pessoa, data_abono, periodo_abono, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
            array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, indicador_certificado, indicador_excluido', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            array('justificativa', 'length', 'max' => 2048),
            array('justificativa_certificacao', 'length', 'max' => 512),
            array('data_hora_certificacao', 'safe'),
            array('nr_abono, id_pessoa, matricula, nr_vinculo, data_abono, periodo_abono, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on' => 'search'),
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
            'JustificativaAjuste' => array(self::BELONGS_TO, 'JustificativaAjuste', 'nr_justificativa'),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
            'Arquivos' => array(self::HAS_MANY, 'ArquivoAjuste', 'nr_abono'),
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
            'data_abono' => 'Data',
            'periodo_abono' => 'Hora',
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
                    'nr_abono',
                    'id_pessoa',
                    'matricula',
                    'data_abono',
                    'periodo_abono',
                    'entrada_saida',
                    'id_pessoa_registro',
                    'data_hora_registro',
                    'ip_registro',
                    'justificativa',
                    'id_pessoa_certificacao',
                    'data_hora_certificacao',
                    'indicador_certificado',
                    'justificativa_certificacao',
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
                $link = $repositorio->devolveLinkExibicao(92, $arquivo->cod_repositorio);
                $listagem .= CHtml::link($arquivo->descricao_arquivo, $link, array('target' => '_blank')).'<br/>';

            }
        }
        else {
            $listagem = 'sem anexos';
        }
        return $listagem;
    }
    
    /**
     * Método para busca de abonos em um determinado período
     * 
     * A partir dos dados recebidos por parâmetro realiza uma busca por abonos
     * de acordo com o período, vínculo e pessoa informados.
     * 
     * O método retorna um array contendo as seguintes chaves: diasComAbono,
     * diasComAbonoPendente e totalAbono.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $nr_vinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @return array Retorna um array contendo os abonos encontrados
     */
    public static function getAbonosMes($id_pessoa, $nr_vinculo, $mes, $ano)
    {
        $abonos = Abono::model()->findAll(array(
            'condition' => "id_pessoa = :id_pessoa 
                            and nr_vinculo = :nr_vinculo
                            and month(data_abono) = :mes
                            and year(data_abono) = :ano
                            and coalesce(indicador_certificado, 'S') = 'S' 
                            and coalesce(indicador_excluido, 'N') = 'N'",
            'params' => array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $nr_vinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
            ),
            'order' => 'data_abono'
        ));

        $diasComAbono = array();
        $diasComAbonoPendente = array();
        $totalAbono = 0;
        foreach ($abonos as $abono) {
            if (!isset($diasComAbono[ date("d/m/Y", strtotime($abono['data_abono'])) ])) {
                $diasComAbono[ date("d/m/Y", strtotime($abono['data_abono'])) ] = 0;
                $diasComAbonoPendente[ date("d/m/Y", strtotime($abono['data_abono'])) ] = false;
            }
            $diasComAbono[ date("d/m/Y", strtotime($abono['data_abono'])) ] += $abono['periodo_abono'];
            $totalAbono += $abono['periodo_abono'];
            if (trim($abono['data_hora_certificacao']) == '') {
                $diasComAbonoPendente[ date("d/m/Y", strtotime($abono['data_abono'])) ] = true;
            }
        }
        
        return array(
            'diasComAbono' => $diasComAbono,
            'diasComAbonoPendente' => $diasComAbonoPendente,
            'totalAbono' => $totalAbono,
        );
    }
    
    /**
     * Método apra busca de afastamentos em um determinado período
     * 
     * A partir dos dados recebidos por parâmetro realiza uma busca por 
     * afastamentos de acordo com o período, vínculo e matrícula informados.
     * 
     * @param int $matricula Chave primária da classe DadoFuncional
     * @param int $vinculo Chave primária da classe DadoFuncional
     * @param string $mes Mês a ser utilizado na busca
     * @param string $ano Ano a ser utilizado na busca
     * @return array Retorna um array contendo os afastamentos encontrados
     */
    public static function getAfastamentos($matricula, $vinculo, $mes, $ano)
    {
        $sql = "SELECT 
                    F.cod_frequencia, TF.nome_frequencia, 
                    F.data_frequencia, F.data_fim_frequencia, F.nr_dias, 
                    DATE_FORMAT(F.data_frequencia, '%d/%m/%Y') data_inicio,
                    DATE_FORMAT(F.data_fim_frequencia, '%d/%m/%Y') data_fim,
                    (DATEDIFF(F.data_fim_frequencia, F.data_frequencia) + 1)
                    -((DATEDIFF(F.data_fim_frequencia, F.data_frequencia) DIV 7) * 2)
                    -(case when DAYOFWEEK(F.data_frequencia) = 1 then 1 else 0 end) -- domingo
                    -(case when DAYOFWEEK(F.data_fim_frequencia) = 7 then 1 else 0 end) -- sabado
                    -(
                        select count(*)
                        from calendario_feriados
                        where
                            DAYOFWEEK(cast(cast(ano as char(4)) + '-' + LTRIM(cast(mes as char(2))) + '-' + LTRIM(cast(Dia as char(2))) as datetime)) not in (1, 7)
                            and cast(cast(ano as char(4)) + '-' + LTRIM(cast(mes as char(2))) + '-' + LTRIM(cast(Dia as char(2))) as datetime)
                                between F.data_frequencia and F.data_fim_frequencia
                    ) as nr_dias_uteis,
                    (case when F.data_frequencia > CURRENT_TIMESTAMP() then 0 else
						(DATEDIFF((case when F.data_fim_frequencia > CURRENT_TIMESTAMP() then CURRENT_TIMESTAMP() else F.data_fim_frequencia end), F.data_frequencia) + 1)
						-((DATEDIFF((case when F.data_fim_frequencia > CURRENT_TIMESTAMP() then CURRENT_TIMESTAMP() else F.data_fim_frequencia end), F.data_frequencia) DIV 7) * 2)
						-(case when DAYOFWEEK(F.data_frequencia) = 1 then 1 else 0 end) -- domingo
						-(case when DAYOFWEEK(case when F.data_fim_frequencia > CURRENT_TIMESTAMP() then CURRENT_TIMESTAMP() else F.data_fim_frequencia end) = 7 then 1 else 0 end) -- sabado
						-(
							select count(*)
							from calendario_feriados
							where
                                DAYOFWEEK(cast(cast(ano as char(4)) + '-' + LTRIM(cast(mes as char(2))) + '-' + LTRIM(cast(Dia as char(2))) as datetime)) not in (1, 7)
								and cast(cast(ano as char(4)) + '-' + LTRIM(cast(mes as char(2))) + '-' + LTRIM(cast(Dia as char(2))) as datetime)
                                    between F.data_frequencia and (case when F.data_fim_frequencia > CURRENT_TIMESTAMP() then CURRENT_TIMESTAMP() else F.data_fim_frequencia end)
						)
					end) as NrDiasUteisAteHoje
                FROM pessoa AS P 
                    INNER JOIN dado_funcional AS DF ON 
                        P.id_pessoa = DF.id_pessoa 
                    INNER JOIN frequencia AS F ON 
                        DF.nr_vinculo = F.nr_vinculo AND DF.matricula = F.matricula
                    INNER JOIN tipo_frequencia AS TF ON 
                        F.cod_frequencia = TF.cod_frequencia 
                WHERE  
                    DF.matricula = :matricula
                    and DF.nr_vinculo = :nr_vinculo
                    and Year(F.data_frequencia) = :ano
                    and Month(F.data_frequencia) = :mes
                ORDER BY 
                    F.data_frequencia";
        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':matricula' => $matricula,
            ':nr_vinculo' => $vinculo,
            ':ano' => $ano,
            ':mes' => $mes,
        ));
    }

}