<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Comportamento criado para tratamento de datas
 * 
 * O Yii Framework disponibiliza a classe {@see CActiveRecordBehavior} para que
 * comportamentos sejam adicionas às classes de modelo que possuem as operações
 * do padrão active record.
 * 
 * Aqui são definidas as operações para manipulação de data dos modelos active
 * record, bem como as ações que devem serem realizadas antes de alguma de salvar,
 * buscar ou validar um modelo.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class ConversorDataBehavior extends CActiveRecordBehavior
{

    const FORMATO_BANCO = 'Y-m-d H:i:s.u';
    const FORMATO_SMALL_BANCO = 'Y-m-d H:i:s';
    const FORMATO_DATA_HORA_BANCO = 'Y-m-d H:i:s';
    const FORMATO_DATA_BANCO = 'Y-m-d';
    const FORMATO_HORA_BANCO = 'H:i';
    const FORMATO_DATA_HORA_BR = 'd/m/Y H:i';
    const FORMATO_DATA_BR = 'd/m/Y';
    const FORMATO_HORA_BR = 'H:i';

    public $atributoOriginal;
    public $atributoData = true, $nomeAtributoData = null;
    public $atributoHora = true, $nomeAtributoHora = null;
    public $atributoDatetime = false, $nomeAtributoDatetime = null;

    /**
     * @var DateTime
     */
    protected $_datetimeOriginal;

    protected function buscaNomeAtributoData()
    {
        if (is_null($this->nomeAtributoData)) {
            return $this->atributoOriginal . "_data";
        } else {
            return $this->nomeAtributoData;
        }
    }

    protected function buscaNomeAtributoHora()
    {
        if (is_null($this->nomeAtributoHora)) {
            return $this->atributoOriginal . "_hora";
        } else {
            return $this->nomeAtributoHora;
        }
    }

    protected function buscaNomeAtributoDatetime()
    {
        if (is_null($this->nomeAtributoDatetime)) {
            return $this->atributoOriginal . "_datetime";
        } else {
            return $this->nomeAtributoDatetime;
        }
    }

    protected function buscaValorOriginal()
    {
        return $this->getOwner()->{$this->atributoOriginal};
    }

    protected function buscaValorData()
    {
        if ($this->atributoData) {
            return $this->getOwner()->{$this->buscaNomeAtributoData()};
        } else
            return null;
    }

    protected function buscaValorHora()
    {
        if ($this->atributoHora) {
            return $this->getOwner()->{$this->buscaNomeAtributoHora()};
        } else {
            return null;
        }
    }

    protected function buscaValorDatetime()
    {
        if ($this->atributoDatetime) {
            return $this->getOwner()->{$this->buscaNomeAtributoDatetime()};
        } else {
            return null;
        }
    }

    /**
     * Método do Yii Framework para busca de modelos
     * 
     * Esse método atualiza os dados de data do modelo para um padrão após uma 
     * busca.
     * 
     * @param CModelEvent $event Parâmetros para o evento de validação
     */
    public function afterFind($event)
    {
        $valorData = $this->getOwner()->{$this->atributoOriginal};

        if (is_null($valorData)) {
            $datetime = $dataFormatada = $horaFormatada = null;
        } else {
            $datetime = DateTime::createFromFormat("!" . self::FORMATO_BANCO, $valorData);
            if ($datetime === false) {
                $datetime = DateTime::createFromFormat("!" . self::FORMATO_SMALL_BANCO, $valorData);
            }

            if ($datetime !== false) {
                $dataFormatada = $datetime->format(self::FORMATO_DATA_BR);
                $horaFormatada = $datetime->format(self::FORMATO_HORA_BR);
            } else {
                $datetime = $dataFormatada = $horaFormatada = null;
            }
        }

        if ($this->atributoData) {
            $this->getOwner()->{$this->buscaNomeAtributoData()} = $dataFormatada;
        }

        if ($this->atributoHora) {
            $this->getOwner()->{$this->buscaNomeAtributoHora()} = $horaFormatada;
        }

        if ($this->atributoDatetime) {
            $this->getOwner()->{$this->buscaNomeAtributoDatetime()} = $datetime;
        }

        $this->_datetimeOriginal = $datetime;
        parent::afterFind($event);
    }

    /**
     * Método do Yii Framework para validação de modelos
     * 
     * Esse método é utilizado para garantir que o modelo a ser salvo contém uma 
     * data no formato correto.
     * 
     * @param CModelEvent $event Parâmetros para o evento de validação
     */
    public function beforeValidate($event)
    {
        // Atribuir um CDbExpression ao atributo original e um caso especial
        if (!($this->getOwner()->{$this->atributoOriginal} instanceof CDbExpression)) {
            // Localizamos qual dos atributos mudou, em ordem de precedencia: o DateTime, o data/hora ou o original
            $novoDatetime = null;

            // Comecamos pelo DateTime
            if ($this->atributoDatetime) {
                $datetime = $this->buscaValorDatetime();

                if ($datetime != $this->_datetimeOriginal) {
                    $novoDatetime = $datetime;
                }
            }

            // Avancamos para o data/hora
            $dataOriginal = (is_null($this->_datetimeOriginal) ? null : $this->_datetimeOriginal->format(self::FORMATO_DATA_BR));
            $horaOriginal = (is_null($this->_datetimeOriginal) ? null : $this->_datetimeOriginal->format(self::FORMATO_HORA_BR));
            if (is_null($novoDatetime) && ( ($this->atributoData && $this->buscaValorData() != $dataOriginal) || ($this->atributoHora && $this->buscaValorHora() != $horaOriginal) )) {
                $data = $this->buscaValorData();
                $hora = $this->buscaValorHora();
                if ($this->atributoData) {
                    if (trim($data) == "") {
                        $novoDatetime = "";
                    } else {
                        if (!$this->atributoHora || trim($hora) == "") {
                            $novoDatetime = DateTime::createFromFormat("!" . self::FORMATO_DATA_BR, $data);
                        } else {
                            $novoDatetime = DateTime::createFromFormat(self::FORMATO_DATA_HORA_BR, $data . " " . $hora);
                        }
                    }
                } elseif (trim($this->buscaValorHora()) == "") {
                    $novoDatetime = "";
                } else {
                    $novoDatetime = DateTime::createFromFormat("!" . self::FORMATO_HORA_BR, $hora);
                }
            }

            // Finalmente, aceitamos o atributo original
            if (is_null($novoDatetime)) {
                if (is_null($this->buscaValorOriginal())) {
                    $novoDatetime = null;
                } else {
                    $novoDatetime = DateTime::createFromFormat(self::FORMATO_BANCO, $this->buscaValorOriginal());
                    if ($novoDatetime === false) {
                        $novoDatetime = DateTime::createFromFormat(self::FORMATO_SMALL_BANCO, $this->buscaValorOriginal());
                    }
                }
            }

            // Agora, podemos nos certificar de que $novoDatetime REALMENTE possui o valor a ser validado
            if ($novoDatetime !== false) {
                if ($novoDatetime === null || (is_string($novoDatetime) && trim($novoDatetime) == "")) {
                    $data = $hora = $dataBanco = null;
                } else {
                    $dataBanco = $novoDatetime->format(self::FORMATO_DATA_HORA_BANCO);
                    $data = $novoDatetime->format(self::FORMATO_DATA_BR);
                    $hora = $novoDatetime->format(self::FORMATO_HORA_BR);
                }

                $this->getOwner()->{$this->atributoOriginal} = $dataBanco;
                if ($this->atributoData) {
                    $this->getOwner()->{$this->buscaNomeAtributoData()} = $data;
                }

                if ($this->atributoHora) {
                    $this->getOwner()->{$this->buscaNomeAtributoHora()} = $hora;
                }

                if ($this->atributoDatetime) {
                    $this->getOwner()->{$this->buscaNomeAtributoDatetime()} = $novoDatetime;
                }
            } else {
                $this->getOwner()->{$this->atributoOriginal} = false;
            }
        }

        parent::beforeValidate($event);
    }

    /**
     * Método do Yii Framework executado antes de salvar um objeto
     * 
     * Esse método garante que uma validação será aplicada na data antes de
     * salvar o modelo que a possui no banco de dados.
     * 
     * @param CModelEvent $event Evento a ser executado pelo Yii Framework
     * @throws CException
     */
    public function beforeSave($event)
    {
        if ($this->buscaValorOriginal() === false) {
            throw new CException("Falha ao salvar data. Valor invalido para o campo " . $this->atributoOriginal);
        }

        parent::beforeSave($event);
    }
}
