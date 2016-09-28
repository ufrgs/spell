<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela v_ponto_e_ajuste
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_seq Número de registro do ponto
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property DateTime $data_hora_ponto Data e hora em que o servidor bateu ponto
 * @property char $entrada_saida Indicador de tipo de registro (E ou S)
 * @property int $id_pessoa_registro Guarda a chave primária da classe Pessoa
 * @property DateTime $data_hora_registro Data atual no formato AAAA-MM-DD HH:MM:SS
 * @property string $ip_registro Endereço de IP do usuário
 * @property string $justificativa Texto de justificativa do ajuste
 * @property int $id_pessoa_certificacao Chave primária da classe Pessoa contendo o código do certificador
 * @property DateTime $data_hora_certificacao Data em que o ajuste foi certificado
 * @property char $indicador_certificado Indicador para o estado de certificação do documento (S ou N)
 * @property char $tipo Indicador de tipo de justificativa
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class PontoEAjuste extends CActiveRecord
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
		return 'v_ponto_e_ajuste';
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
			array('nr_seq, id_pessoa, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
			array('nr_seq', 'length', 'max'=>12),
			array('tipo', 'length', 'max'=>1),
			array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max'=>6),
			array('matricula', 'length', 'max'=>8),
			array('nr_vinculo, entrada_saida, indicador_certificado', 'length', 'max'=>1),
			array('ip_registro', 'length', 'max'=>39),
			array('justificativa', 'length', 'max'=>2048),
			array('data_hora_certificacao', 'safe'),
			array('nr_seq, tipo, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on'=>'search'),
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
			'nr_seq' => 'Nr Seq Registro',
			'tipo' => 'tipo',
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
     * @return CActiveDataProvider Conjunto de dados retornados da consulta
     */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('nr_seq',$this->nr_seq,true);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('id_pessoa',$this->id_pessoa,true);
		$criteria->compare('matricula',$this->matricula,true);
		$criteria->compare('nr_vinculo',$this->nr_vinculo,true);
		$criteria->compare('data_hora_ponto',$this->data_hora_ponto,true);
		$criteria->compare('entrada_saida',$this->entrada_saida,true);
		$criteria->compare('id_pessoa_registro',$this->id_pessoa_registro,true);
		$criteria->compare('data_hora_registro',$this->data_hora_registro,true);
		$criteria->compare('ip_registro',$this->ip_registro,true);
		$criteria->compare('justificativa',$this->justificativa,true);
		$criteria->compare('id_pessoa_certificacao',$this->id_pessoa_certificacao,true);
		$criteria->compare('data_hora_certificacao',$this->data_hora_certificacao,true);
		$criteria->compare('indicador_certificado',$this->indicador_certificado,true);

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
     * @return PontoEAjuste A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Método para busca de registros de um servidor.
     * 
     * Retorna todos os registros feitos por um servidor de acordo com o 
     * período e situação específicados nos parâmetros.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $nr_vinculo Chave primária da classe DadoFuncional
     * @param int $mes Mês a ser utilizado na busca
     * @param int $ano Ano a ser utilizado na busca
     * @param boolean $consolidado TRUE para documento certificado ou FALSE caso contrário
     * @return array Instâncias da classe PontoEAjuste contendo os registros do servidor
     */
    public static function getRegistrosMes($id_pessoa, $nr_vinculo, $mes, $ano, $consolidado = false)
    {
        $restricaoAjustes = "and coalesce(indicador_certificado, 'S') = 'S'
                            and (tipo = 'A'
                                or (tipo = 'R' and not exists (
                                        select 1 from ajuste A
                                        where A.nr_ponto = nr_seq
                                            and coalesce(A.indicador_excluido, 'N') = 'N'
                                    )
                            )) ";
        if ($consolidado) {
            // se e para buscar o consolidado, so interessam ajustes que foram certificados
            $restricaoAjustes = "and (
                                    (tipo = 'A' and indicador_certificado = 'S')
                                    or (tipo = 'R' and not exists (
                                            select 1 from ajuste A
                                            where A.nr_ponto = nr_seq
                                                and A.indicador_certificado = 'S'
                                                and coalesce(A.indicador_excluido, 'N') = 'N'
                                        )
                                ))";
        }
        $registros = PontoEAjuste::model()->findAll(array(
            'condition' => "id_pessoa = :id_pessoa 
                            and nr_vinculo = :nr_vinculo
                            and month(data_hora_ponto) = :mes
                            and year(data_hora_ponto) = :ano
                            $restricaoAjustes ", 
            'params' => array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $nr_vinculo,
                    ':mes' => $mes,
                    ':ano' => $ano,
            ),
            'order' => 'data_hora_ponto'
        ));
        
        return $registros;
    }
}
