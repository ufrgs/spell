<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela ch_mes_servidor
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_carga_horaria Chave primária da classe CargaHorariaMesServidor
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property int $ano Ano em que o registro foi feito
 * @property int $mes Mês em que o registro foi feito
 * @property DateTime $data_inicio_mes Data de início do mês
 * @property int $nr_minutos_trabalho Quantidade de minutos trabalhados
 * @property int $nr_minutos_abono Quantidade em minutos de abono
 * @property int $nr_minutos_afastamento Quantidade em minutos de afastamentos
 * @property int $nr_minutos_previsto Quantidade de minutos de trabalho previsto
 * @property int $nr_minutos_saldo Quantidade de minutos de trabalho sobrando
 * @property int $id_pessoa_atualizacao Identificador da pessoa que atualizou os dados. Chave primária da classe Pessoa
 * @property DateTime $data_atualizacao Data em que uma atualização nos dados foi feita
 * @property string $ip_atualizacao Endereço de IP do usuário
 * @property int $nr_minutos_compensacao Quantidade em minutos de compensações
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class CargaHorariaMesServidor extends CActiveRecord
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
		return 'ch_mes_servidor';
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
			array('id_pessoa, matricula, nr_vinculo, ano, mes, data_inicio_mes, nr_minutos_trabalho, nr_minutos_abono, nr_minutos_compensacao, nr_minutos_afastamento, nr_minutos_previsto, nr_minutos_saldo, id_pessoa_atualizacao, data_atualizacao, ip_atualizacao', 'required'),
			array('id_pessoa, id_pessoa_atualizacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo', 'length', 'max'=>1),
			array('ano', 'length', 'max'=>4),
			array('mes', 'length', 'max'=>2),
			array('nr_minutos_abono, nr_minutos_afastamento, nr_minutos_trabalho, nr_minutos_previsto, nr_minutos_compensacao', 'length', 'max'=>5),
			array('nr_minutos_saldo', 'length', 'max'=>6),
			array('ip_atualizacao', 'length', 'max'=>39),
			array('nr_carga_horaria, id_pessoa, matricula, nr_vinculo, ano, mes, data_inicio_mes, nr_minutos_trabalho, nr_minutos_abono, nr_minutos_compensacao, nr_minutos_afastamento, nr_minutos_previsto, nr_minutos_saldo, id_pessoa_atualizacao, data_atualizacao, ip_atualizacao', 'safe', 'on'=>'search'),
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
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return CargaHorariaMesServidor A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Método para controle de horários do servidor
     * 
     * Esse método calcula o total de horas trabalhadas (registros, ajustes, 
     * abonos, compensacoes) e o total de horas previstas e salva no banco de
     * dados.
     * 
     * @param int $matriculaServidor Chave primária da classe Pessoa
     * @param int $nrVinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @param int $diasUteisMes Quantidade de dias úteis do período pesquisado
     * @return boolean Retorna TRUE se os dados foram salvos com sucesso
     * @throws Exception
     */
    public static function buscaDadosESalva($matriculaServidor, $nrVinculo, $mes, $ano, $diasUteisMes = NULL)
    { 
        // Calcula a quantidade de dias úteis caso não tenha sido informado
        if ($diasUteisMes === NULL) {
            $diasUteisMes = Ponto::getNrDiasUteis($mes, $ano);
        }
        
        $nrVinculo = intval($nrVinculo);
        $pessoa = DadoFuncional::model()->findByPk(array(
            'matricula' => $matriculaServidor,
            'nr_vinculo' => $nrVinculo,
        ));

        if ($pessoa) {            
            if ($pessoa->regime_trabalho == 'DE') {
                $pessoa->regime_trabalho = 40;
            }

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
            // calcula o saldo parcial sem os minutos oriundos de compensação
            $saldo = intval(($jornadaMensal + $tempoAbono) - ($tempoPrevisto - $tempoAfastamento));
            // se o saldo for negativo, adiciona os minutos de compensação até o valor necessário para cobrir o saldo
            if (($saldo < 0) && ($tempoCompensacao > 0)) {
                // se o tempo de compensacao é menor que o necessário para cobrir o saldo, adiciona todo tempo compensado
                if (-$saldo > $tempoCompensacao) {
                    $saldo += $tempoCompensacao;
                }
                // senão, zera o saldo
                else {
                    $saldo = 0;
                }
            }
            $registroCargaMes->nr_minutos_saldo = $saldo;
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
    
    /**
     * Método para consulta de horários de um servidor
     * 
     * O método retorna a quantidade de tempo em minutos que um servidor 
     * trabalhou a mais no mês anterior ao mês vigente.
     * 
     * @return int Quantidade em minutos de horas sobrando
     */
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
     * Método para consulta de horários de um servidor
     * 
     * O método busca todos os registros correspondentes a um servidor em um 
     * determinado período e os retorna em uma array. Todos os parâmetros do 
     * método são utilizados na consulta.
     * 
     * @param int $codPessoa Chave primária da classe Pessoa correspondente ao servidor
     * @param int $nrVinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @return array Lista de objetos CargaHorariaMesServidor contendo os registros de uma pessoa
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
