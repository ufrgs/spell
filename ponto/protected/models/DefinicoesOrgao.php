<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela definicoes_orgao
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property string $id_orgao Chave primária da classe Orgao
 * @property DateTime $hora_inicio_expediente Horário em que o servidor iniciou o expediente
 * @property DateTime $hora_fim_expediente Horário de conclusão do expediente
 * @property char $permite_ocorrencia Indicador para permitir o registro de horas por ocorrência especial
 * @property int $id_pessoa_atualizacao Identificador da pessoa que atualizou o horário. Chave primária da classe Pessoa
 * @property DateTime $data_atualizacao Data em que foi feita a atualização no horário
 * @property DateTime $hora_inicio_expediente_sabado Horário em que o servidor iniciou o expediente em um sábado
 * @property DateTime $hora_fim_expediente_sabado Horário de conclusão do expediente em um sábado
 * @property DateTime $hora_inicio_expediente_domingo Horário em que o servidor iniciou o expediente em um sábado
 * @property DateTime $hora_fim_expediente_domingo Horário de conclusão do expediente em um sábado
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class DefinicoesOrgao extends CActiveRecord
{
    /**
     * Atributo utilizado para indicar a hora inicial do expediente
     * 
     * @var DateTime 
     */
    public $hora_inicio_expediente_hora = null;

    /**
     * Atributo utilizado para indicar a hora inicial do expediente em um sábado
     * 
     * @var DateTime 
     */
    public $hora_inicio_expediente_sabado_hora = null;

    /**
     * Atributo utilizado para indicar a hora inicial do expediente em um domingo
     * 
     * @var DateTime 
     */
    public $hora_inicio_expediente_domingo_hora = null;

    /**
     * Atributo utilizado para indicar a hora final do expediente
     * 
     * @var DateTime 
     */
    public $hora_fim_expediente_hora = null;

    /**
     * Atributo utilizado para indicar a hora final do expediente em um sábado
     * 
     * @var DateTime 
     */
    public $hora_fim_expediente_sabado_hora = null;

    /**
     * Atributo utilizado para indicar a hora final do expediente em um domingo
     * 
     * @var DateTime 
     */
    public $hora_fim_expediente_domingo_hora = null;
    
    /**
     * Variável utilizada para indicar que se o funcionário trabalhou em um sábado
     * 
     * @var boolean 
     */
    public $sabado = null;

    /**
     * Variável utilizada para indicar que se o funcionário trabalhou em um domingo
     * 
     * @var boolean 
     */
    public $domingo = null;
        
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
		return 'definicoes_orgao';
	}

    /**
     * Método do Yii Framework definição de comportamentos do modelo
     * 
     * O array retornado contém as informações necessárias para a configuração
     * dos comportamentos da classe. Aqui são "herdados" os comportamentos da
     * classe {@see ConversorDataBehavior} de manipulçao de datas.
     * 
     * @link http://www.yiiframework.com/wiki/44/behaviors-events/ Explicação sobre Behaviors
     * @link http://www.yiiframework.com/doc/api/1.1/CModel#behaviors-detail Documentação da classe CModel
     * @return array Lista contendo os comportamentos da classe
     */
    public function behaviors() {
            return array(
                'HoraInicioBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente',
                    'atributoData' => false
                ),
                'HoraInicioSabadoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente_sabado',
                    'atributoData' => false
                ),
                'HoraInicioDomingoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_inicio_expediente_domingo',
                    'atributoData' => false
                ),
                'HoraFimBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente',
                    'atributoData' => false
                ),
                'HoraFimSabadoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente_sabado',
                    'atributoData' => false
                ),
                'HoraFimDomingoBehavior' => array(
                    'class' => 'ConversorDataBehavior',
                    'atributoOriginal' => 'hora_fim_expediente_domingo',
                    'atributoData' => false
                ),
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
			array('id_orgao, id_pessoa_atualizacao, data_atualizacao', 'required'),
			array('id_orgao', 'length', 'max'=>5),
			array('permite_ocorrencia', 'length', 'max'=>1),
			array('id_pessoa_atualizacao', 'length', 'max'=>6),
			array('hora_inicio_expediente, hora_inicio_expediente_sabado, '
                            . 'hora_inicio_expediente_domingo, hora_fim_expediente, '
                            . 'hora_fim_expediente_sabado, hora_fim_expediente_domingo', 'safe'),
			array('id_orgao, hora_inicio_expediente, hora_inicio_expediente_sabado, '
                            . 'hora_inicio_expediente_domingo, hora_fim_expediente, hora_fim_expediente_sabado, '
                            . 'hora_fim_expediente_domingo, permite_ocorrencia, id_pessoa_atualizacao, '
                            . 'data_atualizacao', 'safe', 'on'=>'search'),
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
            'Orgao' => array(self::BELONGS_TO, 'Orgao', 'id_orgao'),
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
			'id_orgao' => 'Cod Orgao',
			'hora_inicio_expediente' => 'Hora Inicio Expediente',
                        'hora_inicio_expediente_sabado' => 'Hora Inicio Expediente Sábado',
                        'hora_inicio_expediente_domingo' => 'Hora Inicio Expediente Domingo',
			'hora_fim_expediente' => 'Hora Fim Expediente',
                        'hora_fim_expediente_sabado' => 'Hora Fim Expediente Sábado',
                        'hora_fim_expediente_domingo' => 'Hora Fim Expediente Domingo',
			'permite_ocorrencia' => 'Indicador Permite Ocorrencia',
			'id_pessoa_atualizacao' => 'Cod Pessoa Ultima Atu',
			'data_atualizacao' => 'Data Hora Ultima Atu',
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

		$criteria->compare('id_orgao',$this->id_orgao,false);
		$criteria->compare('hora_inicio_expediente',$this->hora_inicio_expediente,false);
        $criteria->compare('hora_inicio_expediente_sabado',$this->hora_inicio_expediente_sabado,false);
        $criteria->compare('hora_inicio_expediente_domingo',$this->hora_inicio_expediente_domingo,false);
		$criteria->compare('hora_fim_expediente',$this->hora_fim_expediente,false);
        $criteria->compare('hora_fim_expediente_sabado',$this->hora_fim_expediente_sabado,false);
        $criteria->compare('hora_fim_expediente_domingo',$this->hora_fim_expediente_domingo,false);
		$criteria->compare('permite_ocorrencia',$this->permite_ocorrencia,false);
		$criteria->compare('id_pessoa_atualizacao',$this->id_pessoa_atualizacao,false);
		$criteria->compare('data_atualizacao',$this->data_atualizacao,false);

        $criteria->with = array(
            'Orgao' => array('select' => 'nome_orgao, sigla_orgao, matricula_dirigente, matricula_substituto'),
        );
        
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
     * @return DefinicoesOrgao A classe que é Active Record
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Método do Yii Framework para busca de modelos
     * 
     * Esse método verifica verifica se o funcionário buscado trabalhou no final
     * de semana e atribui um valor booleano nas variáveis sabado e ou domingo.
     * 
     * @param CModelEvent $event Parâmetros para o evento de validação
     */
    public function afterFind() {
            
            if (isset($this->hora_inicio_expediente_sabado) && 
                    !is_null($this->hora_inicio_expediente_sabado)){
                $this->sabado = true;
            }
            
            else {
                $this->sabado = false;
            }
            
            if (isset($this->hora_inicio_expediente_domingo) &&
                    !is_null($this->hora_inicio_expediente_domingo)){
                $this->domingo = true;
            }
            
            else {
                $this->domingo = false;
            }
            
            parent::afterFind();
        }
}
