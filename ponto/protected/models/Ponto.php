<?php
/**
 * This is the model class for table "ponto".
 *
 * The followings are the available columns in table 'ponto':
 * @property string $nr_ponto
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $data_hora_ponto
 * @property string $entrada_saida
 * @property string $id_pessoa_registro
 * @property string $data_hora_registro
 * @property string $ip_registro
 * @property string $ambiente_registro
 */
class Ponto extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ponto';
    }

    public function primaryKey()
    {
        return 'nr_ponto';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_pessoa, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
            array('id_pessoa, id_pessoa_registro', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, entrada_saida', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('nr_ponto, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, ambiente_registro', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
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
    public function search($dataProviderOptions = array())
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

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
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Ponto the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getJornada($tipo, $nrVinculo, $codPessoa = NULL)
    {
        if ($codPessoa == NULL) {
            $codPessoa = Yii::app()->session['id_pessoa'];
        }
        
        if ($tipo == 'D') {
            // jornada diaria 
            $restricao = " and DATE_FORMAT(data_hora_ponto, '%d/%m/%Y') = '" . date('d/m/Y') . "' ";
            $cache = 0;
        }
        elseif ($tipo == 'S') {
            // jornada semanal 
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
            // jornada mensal 
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
        // se a ultima entrada foi hoje, conta o tempo da entrada ate agora
        if (($ultimaEntrada != NULL) && (date("d/m/Y", $ultimaEntrada) == date("d/m/Y"))) {
            $jornada += (time() - $ultimaEntrada);
        }
        
        // contabiliza os abonos certificados
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
        $abonos *= 60; // multiplica por 60 para pegar o tempo em segundos
        
        // contabiliza as compensacoes certificadas
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
        $compensacoes *= 60; // multiplica por 60 para pegar o tempo em segundos

        return $jornada + $abonos + $compensacoes;
    }

    public static function getNrDiasUteis($mes, $ano, $ateDiaAtual = false)
    {
        $mes = intval($mes);
        $ano = intval($ano);
        // feriados do mes
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
            // se e para contar ate o dia atual, para de contar quando chega ao dia atual
            if ($ateDiaAtual && ($d == date('d'))) {
                break;
            }
        }
        return $diasUteis;
    }
    
    public static function getCalendarioMes($mes, $ano)
    {
        // feriados do mes
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
            // 1 = domingo e 7 = sabado
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