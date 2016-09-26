<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Interface criada para padronizar a busca de usuários.
 * 
 * O Yii Framework disponibiliza as implementações de autenticação básica através
 * das classe {@see IUserIdentity}. Ela é utilizada de forma indireta na classe
 * {@see Identidade} que foi criada no sistema para adaptar a autenticação 
 * padrão provida pelo framework para as necessidades do sistema.
 * 
 * A IdentidadeInterface define um comportamento a mais específico para esse 
 * sistema que é a busca de pessoas.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
interface IdentidadeInterface
{

    /**
     * Método proposto para buscar e retornar pessoas em alguma fonte de dados.
     * 
     * @return Pessoa Uma instância da classe Pessoa
     */
    public function buscaPessoa();
}
