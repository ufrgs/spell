<?php

/**
 * This is the model class for table "abono".
 *
 * The followings are the available columns in table 'abono':
 * @property string $nr_abono
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $data_abono
 * @property string $periodo_abono
 * @property string $id_pessoa_registro
 * @property string $data_hora_registro
 * @property string $ip_registro
 * @property string $justificativa
 * @property string $id_pessoa_certificacao
 * @property string $data_hora_certificacao
 * @property string $indicador_certificado
 * @property string $nr_justificativa
 * @property string $justificativa_certificacao
 */
class Abono extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'abono';
    }

    public function primaryKey()
    {
        return 'nr_abono';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_pessoa, data_abono, periodo_abono, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
            array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, indicador_certificado, indicador_excluido', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            array('justificativa', 'length', 'max' => 2048),
            array('justificativa_certificacao', 'length', 'max' => 512),
            array('data_hora_certificacao', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('nr_abono, id_pessoa, matricula, nr_vinculo, data_abono, periodo_abono, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on' => 'search'),
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
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
            'JustificativaAjuste' => array(self::BELONGS_TO, 'JustificativaAjuste', 'nr_justificativa'),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
            'Arquivos' => array(self::HAS_MANY, 'ArquivoAjuste', 'nr_abono'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
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
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Ajuste the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

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