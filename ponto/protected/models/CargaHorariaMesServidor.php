<?php

/**
 * This is the model class for table "ch_mes_servidor".
 *
 * The followings are the available columns in table 'ch_mes_servidor':
 * @property string $nr_carga_horaria
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $ano
 * @property string $mes
 * @property string $data_inicio_mes
 * @property string $nr_minutos_trabalho
 * @property string $nr_minutos_abono
 * @property string $nr_minutos_afastamento
 * @property string $nr_minutos_previsto
 * @property string $nr_minutos_saldo
 * @property string $id_pessoa_atualizacao
 * @property string $data_atualizacao
 * @property string $ip_atualizacao
 */
class CargaHorariaMesServidor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ch_mes_servidor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, matricula, nr_vinculo, ano, mes, data_inicio_mes, nr_minutos_trabalho, nr_minutos_abono, nr_minutos_compensacao, nr_minutos_afastamento, nr_minutos_previsto, nr_minutos_saldo, id_pessoa_atualizacao, data_atualizacao, ip_atualizacao', 'required'),
			array('id_pessoa, id_pessoa_atualizacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo', 'length', 'max'=>1),
			array('ano', 'length', 'max'=>4),
			array('mes', 'length', 'max'=>2),
			array('nr_minutos_abono, nr_minutos_afastamento, nr_minutos_trabalho, nr_minutos_previsto, nr_minutos_compensacao', 'length', 'max'=>5),
			array('nr_minutos_saldo', 'length', 'max'=>6),
			array('ip_atualizacao', 'length', 'max'=>39),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nr_carga_horaria, id_pessoa, matricula, nr_vinculo, ano, mes, data_inicio_mes, nr_minutos_trabalho, nr_minutos_abono, nr_minutos_compensacao, nr_minutos_afastamento, nr_minutos_previsto, nr_minutos_saldo, id_pessoa_atualizacao, data_atualizacao, ip_atualizacao', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nr_carga_horaria' => 'Nr Seq Carga Horaria',
			'id_pessoa' => 'Cod Pessoa',
			'matricula' => 'Matricula Servidor',
			'nr_vinculo' => 'Nr Vinculo',
			'ano' => 'ano',
			'mes' => 'mes',
			'data_inicio_mes' => 'Data Inicio mes',
			'nr_minutos_trabalho' => 'Tempo Trabalho',
			'nr_minutos_abono' => 'Tempo Abono',
			'nr_minutos_compensacao' => 'Tempo Compensado',
			'nr_minutos_afastamento' => 'Tempo Afastamento',
			'nr_minutos_previsto' => 'Tempo Previsto',
			'nr_minutos_saldo' => 'Saldo',
			'id_pessoa_atualizacao' => 'Cod Pessoa Ultima Atu',
			'data_atualizacao' => 'Data Ultima Atu',
			'ip_atualizacao' => 'Ipultima Atu',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nr_carga_horaria',$this->nr_carga_horaria,false);
		$criteria->compare('id_pessoa',$this->id_pessoa,false);
		$criteria->compare('matricula',$this->matricula,false);
		$criteria->compare('nr_vinculo',$this->nr_vinculo,false);
		$criteria->compare('ano',$this->ano,false);
		$criteria->compare('mes',$this->mes,false);
		$criteria->compare('data_inicio_mes',$this->data_inicio_mes,false);
		$criteria->compare('nr_minutos_trabalho',$this->nr_minutos_trabalho,false);
		$criteria->compare('nr_minutos_abono',$this->nr_minutos_abono,false);
		$criteria->compare('nr_minutos_compensacao',$this->nr_minutos_compensacao,false);
		$criteria->compare('nr_minutos_afastamento',$this->nr_minutos_afastamento,false);
		$criteria->compare('nr_minutos_previsto',$this->nr_minutos_previsto,false);
		$criteria->compare('nr_minutos_saldo',$this->nr_minutos_saldo,false);
		$criteria->compare('id_pessoa_atualizacao',$this->id_pessoa_atualizacao,false);
		$criteria->compare('data_atualizacao',$this->data_atualizacao,false);
		$criteria->compare('ip_atualizacao',$this->ip_atualizacao,false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CargaHorariaMesServidor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Calcula o total de horas trabalhadas (registros, ajustes, abonos, compensacoes) e o total de horas previstas e salva na tabela
     * @throws Exception
     * @param type $matriculaServidor
     * @param type $nrVinculo
     * @param type $mes
     * @param type $ano
     * @param type $diasUteisMes
     */
    public static function buscaDadosESalva($matriculaServidor, $nrVinculo, $mes, $ano, $diasUteisMes = NULL) // throws Exception
    { 
        if ($diasUteisMes === NULL) {
            // se nao recebeu o parametro, calcula
            $diasUteisMes = Ponto::getNrDiasUteis($mes, $ano);
        }
        
        $nrVinculo = intval($nrVinculo);
        $pessoa = DadoFuncional::model()->findByPk(array(
            'matricula' => $matriculaServidor,
            'nr_vinculo' => $nrVinculo,
        ));

        if ($pessoa) {            
            if ($pessoa->regime_trabalho == 'DE')
                $pessoa->regime_trabalho = 40;

            $cargaHorariaMensal = ($pessoa->regime_trabalho/5)*$diasUteisMes; // regime de trabalho / 5 * numero de dias uteis no mes
            // PROVISORIO
            // Devido a quarta-feira de cinzas, que e de meio turno, diminui 4 horas se o mes e fevereiro
            if ($mes == 2)
                $cargaHorariaMensal -= 4;

            $tempoPrevisto = $cargaHorariaMensal * 60;
            // abonos
            $command = Yii::app()->db->createCommand();
            $tempoAbono = $command
                ->select('sum(periodo_abono)')
                ->from('abono')
                ->where("
                    matricula = :matricula 
                    and nr_vinculo = :nr_vinculo 
                    and indicador_certificado = 'S'
                    and coalesce(indicador_excluido, 'N') = 'N'
                    and month(data_abono) = :mes
                    and year(data_abono) = :ano
                ", array(
                    ':matricula' => $matriculaServidor,
                    ':nr_vinculo' => $nrVinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
                ))->queryScalar();
            
            // compensacoes
            $command = Yii::app()->db->createCommand();
            $tempoCompensacao = $command
                ->select('sum(periodo_compensacao)')
                ->from('compensacao')
                ->where("
                    matricula = :matricula 
                    and nr_vinculo = :nr_vinculo 
                    and indicador_certificado = 'S'
                    and coalesce(indicador_excluido, 'N') = 'N'
                    and month(data_compensacao) = :mes
                    and year(data_compensacao) = :ano
                ", array(
                    ':matricula' => $matriculaServidor,
                    ':nr_vinculo' => $nrVinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
                ))->queryScalar();

            // afastamentos
            $horasAfastamento = 0;
            $afastamentos = Abono::getAfastamentos($matriculaServidor, $nrVinculo, $mes, $ano);
            if (!empty($afastamentos)) {
                foreach ($afastamentos as $afastamento) {
                    $horasAfastamento += ($afastamento['nr_dias_uteis'] * $pessoa->regime_trabalho/5);
                }
            }
            $tempoAfastamento = $horasAfastamento * 60;
            
            // registros
            $registros = PontoEAjuste::getRegistrosMes($pessoa->id_pessoa, $nrVinculo, $mes, $ano, true);

            $ultimoTipoRegistro = 'S';
            $ultimoRegistro = NULL;
            $jornadaMensal = 0;
            foreach ($registros as $registro) {
                if ($registro->entrada_saida == 'S') {
                    if ($ultimoTipoRegistro == 'E') {
                        $jornadaDoTurno = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;
                        $jornadaMensal += $jornadaDoTurno;
                    }
                }
                $ultimoTipoRegistro = $registro->entrada_saida;
                $ultimoRegistro = $registro->data_hora_ponto; 
            }
            
            // se existe registro, busca, senao cria
            $registroCargaMes = CargaHorariaMesServidor::model()->findByAttributes(array(
                'matricula' => $pessoa->matricula,
                'nr_vinculo' => $pessoa->nr_vinculo,
                'ano' => $ano,
                'mes' => $mes,
            ));
            if (empty($registroCargaMes)) {
                $registroCargaMes = new CargaHorariaMesServidor();
                $registroCargaMes->id_pessoa = $pessoa->id_pessoa;
                $registroCargaMes->matricula = $pessoa->matricula;
                $registroCargaMes->nr_vinculo = $pessoa->nr_vinculo;
                $registroCargaMes->ano = $ano;
                $registroCargaMes->mes = $mes;
                $registroCargaMes->data_inicio_mes = $ano.'-'.$mes.'-01';
            }
            $registroCargaMes->nr_minutos_trabalho = intval($jornadaMensal);
            $registroCargaMes->nr_minutos_abono = intval($tempoAbono);
            $registroCargaMes->nr_minutos_compensacao = intval($tempoCompensacao);
            $registroCargaMes->nr_minutos_afastamento = intval($tempoAfastamento);
            $registroCargaMes->nr_minutos_previsto = intval($tempoPrevisto - $tempoAfastamento);
            $registroCargaMes->nr_minutos_saldo = intval(($jornadaMensal + $tempoAbono + $tempoCompensacao) - ($tempoPrevisto - $tempoAfastamento));
            $registroCargaMes->id_pessoa_atualizacao = (isset(Yii::app()->session['id_pessoa']) ? Yii::app()->session['id_pessoa'] : 0);
            $registroCargaMes->data_atualizacao = new CDbExpression('CURRENT_TIMESTAMP()');
            $registroCargaMes->ip_atualizacao = $_SERVER['REMOTE_ADDR'];

            if ($registroCargaMes->save(true, array(
                    'id_pessoa', 'matricula', 'nr_vinculo', 'ano', 'mes', 'data_inicio_mes', 
                    'nr_minutos_trabalho', 'nr_minutos_abono', 'nr_minutos_compensacao', 'nr_minutos_afastamento', 
                    'nr_minutos_previsto', 'nr_minutos_saldo', 'id_pessoa_atualizacao', 'data_atualizacao', 'ip_atualizacao'
                ))) {
                return true;
            }
            else {
                throw new Exception("($matriculaServidor, $nrVinculo): ".print_r($registroCargaMes->getErrors(), true));
            }
        }
        else {
            throw new Exception("Servidor não encontrado ($matriculaServidor, $nrVinculo)");
        }
    }
    
    public function getSaldoMesAnterior()
    {
        $chMesAnterior = CargaHorariaMesServidor::model()->find(array(
            'select' => 'nr_minutos_saldo',
            'condition' => 'id_pessoa = :id_pessoa and nr_vinculo = :nr_vinculo and mes = :mes and ano = :ano',
            'params' => array(
                ':id_pessoa' => $this->id_pessoa,
                ':nr_vinculo' => $this->nr_vinculo,
                ':mes' => ($this->mes > 1 ? $this->mes-1 : 12),
                ':ano' => ($this->mes > 1 ? $this->ano : $this->ano-1),
            ),
        ));
        
        return (!empty($chMesAnterior) ? $chMesAnterior->nr_minutos_saldo : 0);
    }
    
    /**
     * Retorna a carga horaria cumprida no mes/ano
     * @param int $codPessoa
     * @param int $nrVinculo
     * @param int $mes
     * @param int $ano
     * @return int
     */
    public static function getCargaHorariaMes($codPessoa, $nrVinculo, $mes, $ano)
    {
        $chMes = CargaHorariaMesServidor::model()->find(array(
            'condition' => 'id_pessoa = :id_pessoa and nr_vinculo = :nr_vinculo and mes = :mes and ano = :ano',
            'params' => array(
                ':id_pessoa' => $codPessoa,
                ':nr_vinculo' => $nrVinculo,
                ':mes' => $mes,
                ':ano' => $ano,
            ),
        ));
        
        return $chMes;
    }
}
