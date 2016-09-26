<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela ponto
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_ponto Chave primária da classe Ponto
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property DateTime $data_hora_ponto Data e hora em que o servidor bateu ponto
 * @property char $entrada_saida Indicador de tipo de registro (E ou S)
 * @property int $id_pessoa_registro Guarda a chave primária da classe Pessoa
 * @property DateTime $data_hora_registro Data atual no formato AAAA-MM-DD HH:MM:SS
 * @property string $ip_registro Endereço de IP do usuário
 * @property string $ambiente_registro Informações do computador no qual o registro foi feito
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Ponto extends CActiveRecord
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
        return 'ponto';
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
        return 'nr_ponto';
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
            array('id_pessoa, id_pessoa_registro', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, entrada_saida', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            array('nr_ponto, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, ambiente_registro', 'safe', 'on' => 'search'),
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
            'nr_ponto' => 'Nr Seq Ponto',
            'id_pessoa' => 'Cod Pessoa',
            'matricula' => 'Matricula Servidor',
            'nr_vinculo' => 'Nr Vinculo',
            'data_hora_ponto' => 'Data Hora Ponto',
            'entrada_saida' => 'Indicador Entrada Saida',
            'id_pessoa_registro' => 'Cod Pessoa Registro',
            'data_hora_registro' => 'Data Hora Registro',
            'ip_registro' => 'Ipregistro',
            'ambiente_registro' => 'Agente',
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

        $criteria->compare('nr_ponto', $this->nr_ponto, false);
        $criteria->compare('t.id_pessoa', $this->id_pessoa, false);
        $criteria->compare('matricula', $this->matricula, false);
        $criteria->compare('nr_vinculo', $this->nr_vinculo, false);
        $criteria->compare('data_hora_ponto', $this->data_hora_ponto, true);
        $criteria->compare('entrada_saida', $this->entrada_saida, true);
        $criteria->compare('id_pessoa_registro', $this->id_pessoa_registro, false);
        $criteria->compare('data_hora_registro', $this->data_hora_registro, true);
        $criteria->compare('ip_registro', $this->ip_registro, true);
        $criteria->compare('ambiente_registro', $this->ambiente_registro, true);

        $criteria->with = array(
            'Pessoa' => array('select' => 'nome_pessoa')
        );
        
        return new CActiveDataProvider($this, array_merge(array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'nr_ponto',
                    'id_pessoa',
                    'matricula',
                    'data_hora_ponto',
                    'entrada_saida',
                    'id_pessoa_registro',
                    'data_hora_registro',
                    'ip_registro',
                    'ambiente_registro',
                    'Pessoa.nome_pessoa' => array(
                        'asc' => 'Pessoa.nome_pessoa asc',
                        SORT_ASC => 'Pessoa.nome_pessoa asc',
                        'desc' => 'Pessoa.nome_pessoa desc',
                        SORT_DESC => 'Pessoa.nome_pessoa desc',
                    )
                ),
                'defaultOrder' => array(
                    'nr_ponto' => CSort::SORT_DESC,
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
     * @return Ponto A classe que é Active Record
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Método para manipulação de datas.
     * 
     * Esse método calcula o total de horas abonasdas, total de horas compensadas
     * e o total de horas trabalhadas de um servidor e retorna essa quantidade.
     * 
     * O método deve receber o tipo de período a ser calculado, número do vínculo
     * do servidor e seu código único.
     * 
     * Se o parâmetro $tipo contém o valor <code>D</code> o método calculará
     * a jornada diária, se o valor for <code>S</code> calculará a jornada semanal.
     * Caso outro valor seja passado será calculada a jornada mensal.
     * 
     * @param char $tipo Indicador de período.
     * @param int $nrVinculo Chave primária da classe DadoFuncional
     * @param int $codPessoa Chave primária da classe Pessoa
     * @return int Tempo total da jornada do servidor
     */
    public static function getJornada($tipo, $nrVinculo, $codPessoa = NULL)
    {
        if ($codPessoa == NULL) {
            $codPessoa = Yii::app()->session['id_pessoa'];
        }
        
        if ($tipo == 'D') {
            // Jornada diária 
            $restricao = " and DATE_FORMAT(data_hora_ponto, '%d/%m/%Y') = '" . date('d/m/Y') . "' ";
            $cache = 0;
        }
        elseif ($tipo == 'S') {
            // Jornada semanal 
            $restricao = " and data_hora_ponto between 
                            (case
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 0 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -1 DAY) -- segunda
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 1 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -2 DAY) -- terca
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 2 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -3 DAY) -- quarta
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 3 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -4 DAY) -- quinta
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 4 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -5 DAY) -- sexta
                                when WEEKDAY(CURRENT_TIMESTAMP()) = 5 then DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -6 DAY) -- sabado
                                else CURRENT_TIMESTAMP()
                            end)
                            and CURRENT_TIMESTAMP() ";
        }
        else {
            // Jornada mensal 
            $restricao = " and month(data_hora_ponto) = " . intval(date('m')) . " ";
        }

        $registros = PontoEAjuste::model()->findAll(array(
            'select' => 'data_hora_ponto, entrada_saida',
            'condition' => "id_pessoa = :id_pessoa
                            and nr_vinculo = :nr_vinculo 
                            and (tipo = 'R' or 
                            (tipo = 'A' and coalesce(indicador_certificado, 'N') = 'S'))
                            $restricao",
            'params' => array(
                ':id_pessoa' => $codPessoa,
                ':nr_vinculo' => $nrVinculo,
            ),
            'order' => 'data_hora_ponto'
        ));

        $ultimaEntrada = NULL;
        $jornada = 0;
        foreach ($registros as $registro) {
            if (($ultimaEntrada != NULL) && ($registro->entrada_saida == 'S')) {
                // faz calculo da jornada
                $ultimaSaida = strtotime($registro->data_hora_ponto);
                $jornada += ($ultimaSaida - $ultimaEntrada);
                $ultimaEntrada = NULL;
            }
            elseif ($registro->entrada_saida == 'E') {
                $ultimaEntrada = strtotime($registro->data_hora_ponto);
            }
        }
        // Se a última entrada foi hoje, conta o tempo da entrada até agora
        if (($ultimaEntrada != NULL) && (date("d/m/Y", $ultimaEntrada) == date("d/m/Y"))) {
            $jornada += (time() - $ultimaEntrada);
        }
        
        // Contabiliza os abonos certificados
        $sql = "select sum(periodo_abono)
                from abono
                where
                    id_pessoa = :id_pessoa
                    and nr_vinculo = :nr_vinculo
                    and coalesce(indicador_certificado, 'N') = 'S'
                    and coalesce(indicador_excluido, 'N') = 'N'
                    ".str_replace('data_hora_ponto', 'data_abono', $restricao);
        $abonos = Yii::app()->db->createCommand($sql)->queryScalar(array(
            ':id_pessoa' => $codPessoa,
            ':nr_vinculo' => $nrVinculo,
        ));
        $abonos *= 60; // Multiplica por 60 para pegar o tempo em segundos
        
        // Contabiliza as compensações certificadas
        $sql = "select sum(periodo_compensacao)
                from compensacao
                where
                    id_pessoa = :id_pessoa
                    and nr_vinculo = :nr_vinculo
                    and coalesce(indicador_certificado, 'N') = 'S'
                    and coalesce(indicador_excluido, 'N') = 'N'
                    ".str_replace('data_hora_ponto', 'data_compensacao', $restricao);
        $compensacoes = Yii::app()->db->createCommand($sql)->queryScalar(array(
            ':id_pessoa' => $codPessoa,
            ':nr_vinculo' => $nrVinculo,
        ));
        $compensacoes *= 60; // Multiplica por 60 para pegar o tempo em segundos

        return $jornada + $abonos + $compensacoes;
    }

    /**
     * Método para manipulação de datas.
     * 
     * Esse método faz a contagem de dias úteis em um determinado perído de tempo.
     * 
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @param boolean $ateDiaAtual Indicador para poder incluir no resultado o dia atual
     * @return int Quantide de dias úteis dentro do período pesquisado
     */
    public static function getNrDiasUteis($mes, $ano, $ateDiaAtual = false)
    {
        $mes = intval($mes);
        $ano = intval($ano);
        
        // Feriados do mês
        $feriados = array();
        $sql = "select dia from calendario_feriados
                where ano = $ano and mes = $mes ";
        $query = Yii::app()->db->createCommand($sql)->queryAll();
        
        foreach($query as $feriado) {
            $feriados[] = $feriado['Dia'];
        }
        
        // Total de dias no mes
        $diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        $diasUteis = 0;
        
        for ($d = 1; $d <= $diasMes; $d++) {
            $diaSemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $d, $ano), 0);
            
            // 0 = domingo e 6 = sabado
            if (($diaSemana != 0) && ($diaSemana != 6) && !in_array($d, $feriados)) {
                $diasUteis++;
            }
            
            // Se é para contar até o dia atual, para de contar quando chega ao dia atual
            if ($ateDiaAtual && ($d == date('d'))) {
                break;
            }
        }
        
        return $diasUteis;
    }
    
    /**
     * Método para manipulação de datas.
     * 
     * Esse método cria um array contendo as datas válidas de dias úteis, 
     * feriados e finais de semana do mês.
     * 
     * Cada dia do calendrário tem os seguintes elementos: Data, Dia, DiaSemana,
     * MinutosRegistro, MinutosAbono, AbonoPendente, MinutosCompensacao, 
     * CompensacaoPendente, Feriado, EmAfastamento, Afastamentos e Registros.
     * 
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @return array Array representando um calendário do período informado
     */
    public static function getCalendarioMes($mes, $ano)
    {
        // Feriados do mês
        $feriados = array();
        $sql = "select dia from calendario_feriados
                where ano = $ano and mes = $mes ";
        $query = Yii::app()->db->createCommand($sql)->queryAll();
        
        foreach($query as $feriado) {
            $feriados[] = $feriado['Dia'];
        }
        
        $calendario = array();
        $diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        for ($d = 1; $d <= $diasMes; $d++) {
            $diaSemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $d, $ano), 0)+1;
            
            // 1 = domingo e 7 = sábado
            $calendario[$d] = array(
                'Data' => str_pad($d, 2, "0", STR_PAD_LEFT).'/'.str_pad($mes, 2, "0", STR_PAD_LEFT).'/'.$ano, 
                'Dia' => $d, 
                'DiaSemana' => $diaSemana,
                'MinutosRegistro' => 0,
                'MinutosAbono' => 0,
                'AbonoPendente' => false,
                'MinutosCompensacao' => 0,
                'CompensacaoPendente' => false,
                'Feriado' => in_array($d, $feriados),
                'EmAfastamento' => false,
                'Afastamentos' => '',
                'Registros' => array(),
            );
        }
        
        return $calendario;
    }

}