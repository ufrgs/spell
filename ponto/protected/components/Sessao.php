<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Filtro para controle da sessão do usuário
 * 
 * Essa classe é uma derivação da classe CFilter do Yii Framework utilizada para 
 * garantir que o usuário está logado com uma sessão válida.
 * 
 * Antes de cada requisição o método <code>preFilter()</code> é invocado.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class Sessao extends CFilter
{

    /**
     * Atributo que guarda uma lista com as classes que contém os dados 
     * utilizados para validação.
     * 
     * É usado o padrão nome_da_chave_primaria => nome_da_classe.
     * 
     * @var array Lista com os modelos utilizados
     */
    public $modelos = array("id_pessoa" => "Pessoa");

    /**
     * Método contendo o código utilizado para filtragem de sessão.
     * 
     * Esse método sobrescreve o método do Yii Framework para conter o código 
     * necessário para verificar a sessão do usuário.
     * 
     * @param CFilterChain Cadeia de filtros a serem executados
     * @return boolean Retorna TRUE para sessão válida e FALSE para inválida
     * @throws CHttpException Excessão com código 403 indicando sessão expirada
     */
    public function preFilter($filterChain)
    {
        if (is_null(Yii::app()->user->getId())) {
            $id = new Identidade();
            $id->defineModelos($this->modelos);
            if ($id->authenticate()) {
                Yii::app()->user->login($id);
            } else {
                throw new CHttpException(403, "Sessão expirada.");
            }
        }
        return true;
    }
}
