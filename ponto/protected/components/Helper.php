<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Classe contendo método para uso geral da aplicação.
 * 
 * Aqui são implementados métodos para verificação de endereços de IP, formatação
 * de datas e horas, busca de órgãos da instituição, correção de acentuação e 
 * métodos para auxiliar a visualização de dados das páginas.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class Helper {

    /**
     * Método auxiliar para as views da aplicação.
     * 
     * A função recebe um determiando tempo em minutos e verifica se seu valor
     * é NULL. Se não for NULL o mesmo é mostrado na tela, caso contrário uma
     * mensagem é mostrada em seu lugar.
     * 
     * Esse método é utilizado na página de horários no arquivo exibirHorariosOrgaos 
     * para exibir os horários de início e fim do expediente do servidor. A 
     * mensagem de "Não cadastrado" é exibida quando o servidor não registrou
     * o seu horário em um dia da semana, por exemplo.
     * 
     * @param string $horario Horário a ser exibido
     */
    public static function HorarioOrgao($horario)
    {
        if ($horario == NULL) {
            echo "Não cadastrado";
        } else {
            echo $horario;
        }
    }

    /**
     * Método para manipulação de texto.
     * 
     * A função recebe uma string e converte a codificação dela para o formato
     * ASCII//TRANSLIT utilizando a função iconv do PHP 5.0.
     * 
     * @link https://secure.php.net/manual/pt_BR/book.iconv.php
     * @param string $string Texto a acentuação removida
     * @return type
     */
	public static function tiraAcento($string) {
		$string = iconv(mb_detect_encoding($string, mb_detect_order(),TRUE), 'ASCII//TRANSLIT', $string);
		$string = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $string);
		return $string;
	}
    
    /**
     * Método para manipulação de datas.
     * 
     * A função recebe um determiando tempo em minutos e o transforma em horas 
     * utlizando o formato HH:MM.
     * 
     * @param int $tempoEmMinutos Tempo a ser convertido em horas
     * @return string A hora no formato HH:MM
     */
    public static function transformaEmFormatoHora($tempoEmMinutos) {
        $horas = intval($tempoEmMinutos/60);
        $minutos = abs($tempoEmMinutos % 60);
        $minutos = str_pad($minutos, 2, "0", STR_PAD_LEFT);    
        if (($tempoEmMinutos < 0) && ($horas == 0))
            $horas = '-'.$horas;
        return($horas.":".$minutos);
    }

    public static function formataHorarioAcompanhamento($registroAtual, $dataAuxiliar, $tipoRegistroInexistente, $codPessoaRegistro) {
        if ($registroAtual != NULL):
            if (($codPessoaRegistro == Yii::app()->session['id_pessoa']) && ($registroAtual->tipo == 'R')): ?>
                <a onclick="ajusta(<?=$registroAtual->nr_seq?>)" title="Solicitar ajuste do registro"><?=date('H:i', strtotime($registroAtual->data_hora_ponto))?> <i class="fa fa-pencil"></i></a>
        <?  else:
                if ($registroAtual->indicador_certificado == 'S'): ?>
                    <span class="textoVerde" title="Pedido de ajuste indicador_certificado"><?=date('H:i', strtotime($registroAtual->data_hora_ponto))?></span>
            <?  elseif ($registroAtual->tipo == 'A'):    ?>
                    <span class="textoAmarelo" title="Pedido de ajuste pendente de certificação"><?=date('H:i', strtotime($registroAtual->data_hora_ponto))?></span>
            <?  else: ?>
                    <?=date('H:i', strtotime($registroAtual->data_hora_ponto))?>
            <?  endif;
            endif;
        else:
            if ($codPessoaRegistro == Yii::app()->session['id_pessoa']):  ?>
                <a onclick="ajusta(0, '<?=$tipoRegistroInexistente?>|<?=date('d/m/Y', strtotime($dataAuxiliar))?>')" title="Solicitar inclusão de registro"><i class="aviso fa fa-warning"></i> <i class="fa fa-pencil"></i></a>
        <?  else: ?>
                <i class="aviso fa fa-warning"></i>
        <?  endif;
        endif;
    }

    /**
     * Método auxiliar para as views da aplicação.
     * 
     * É utilizado na tela de ajustes na página dadosPedido.
     * 
     * Esse método retorna código HTML mostrando informaçõe sobre o período
     * trabalhado pelo servidor. São essas informações total trabalhado e 
     * a jornada de trabalho da pessoa.
     * 
     * @param int $jornadaDiaria Valor representando a jornada do servidore
     * @param array $diasComAbono Lista de dias com pedidos de abono
     * @param int $ultimoDia Data do último registro trabalhado
     */
    public static function mostraTotalTrabalhado($jornadaDiaria, $diasComAbono, $ultimoDia) {
        ?>
        <tr>
            <td class="alinhaDireita" colspan="4">
                Total trabalhado:
                <? if (isset($diasComAbono[$ultimoDia])): ?>
                    <br/>Horas abonadas:&nbsp;
                <? endif; ?>
            </td>
            <td class="alinhaDireita">
            <?  if ($jornadaDiaria == 0): ?>
                    <span>?</span>
            <?  else: ?>
                    <span <?=($jornadaDiaria > 600 ? 'class="textoVermelho" title="Jornada diária superior a 10 horas"' : '')?>><?=Helper::transformaEmFormatoHora($jornadaDiaria)?></span>
                <? endif; ?>
                <? if (isset($diasComAbono[$ultimoDia])): ?>
                    <br/><span><?=Helper::transformaEmFormatoHora($diasComAbono[$ultimoDia])?>&nbsp;</span>
                <? endif; ?>
            </td>
        </tr>
        <?
    }

    /**
     * Método auxliar para maniplação de datas.
     * 
     * Wrapper para o método {@see Helper::diaFormatado($dia)}.
     * 
     * @return string Retorna string contendo a data por extenso
     */
    public static function diaDeHoje()
    {
        return self::diaFormatado(time());
    }

    /**
     * Método para manipulação de datas.
     * 
     * Recebe por parâmetro uma data gerada a partir de uma das funções de data 
     * do PHP e converte os dia da semana e mês numéricos para sua forma extensa.
     * 
     * Por exemplo, se o método for chamado como diaFormatado(time()) será 
     * retornado algo como: Segunda-feira, 01 de setembro.
     * 
     * Se o parâmetro $comAno for definido como TRUE o ano será
     * anexado ao final da frase.
     * 
     * @param int $dia Unidade de tempo correspondente ao dia
     * @param boolean $comAno Indicador de retorno do ano. Use TRUE para retornar
     * @return string Retorna uma string contendo a data formatada por extenso
     */
    public static function diaFormatado($dia, $comAno = false)
    {
        switch (date("w", $dia)) {
            case 0:
                $diaSemana = "Domingo";
                break;
            case 1:
                $diaSemana = "Segunda-feira";
                break;
            case 2:
                $diaSemana = "Terça-feira";
                break;
            case 3:
                $diaSemana = "Quarta-feira";
                break;
            case 4:
                $diaSemana = "Quinta-feira";
                break;
            case 5:
                $diaSemana = "Sexta-feira";
                break;
            case 6:
                $diaSemana = "Sábado";
                break;
        }
        switch (date("m", $dia)) {
            case 1:
                $mes = "janeiro";
                break;
            case 2:
                $mes = "fevereiro";
                break;
            case 3:
                $mes = "março";
                break;
            case 4:
                $mes = "abril";
                break;
            case 5:
                $mes = "maio";
                break;
            case 6:
                $mes = "junho";
                break;
            case 7:
                $mes = "julho";
                break;
            case 8:
                $mes = "agosto";
                break;
            case 9:
                $mes = "setembro";
                break;
            case 10:
                $mes = "outubro";
                break;
            case 11:
                $mes = "novembro";
                break;
            case 12:
                $mes = "dezembro";
                break;
        }

        return $diaSemana . ", " . date("d", $dia) . " de " . $mes . ($comAno ? " de " . date("Y", $dia) : '');
    }

    /**
     * Método para menipuação de endereços de IP.
     * 
     * Essa função verifica se um endereço de IP pertence a uma subrede (range).
     * 
     * @param string $ip O endereço de IP a ser verificado
     * @param string $range Espectro de valores permitidos
     * @return boolean Retorna TRUE para inidicar que o IP está contido no espectro
     */
    public static function ip_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }
    
    /**
     * Método para manipulação de órgãos da instituição.
     * 
     * Retorna um array com a hierarquia de órgaos que libera a id_aplicacao 
     * para a pessoa.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $id_aplicacao Chave primária da aplicação
     * @return array Lista de órgãos que o servidor tem permisãao
     */
    public static function getHierarquiaOrgaosPermissao($id_pessoa, $id_aplicacao)
    {
        $orgaos = array();
        $permissao = Permissao::model()->find('id_pessoa = :id_pessoa AND id_aplicacao = :id_aplicacao AND data_expiracao IS NULL', array(
            ':id_pessoa' => $id_pessoa,
            ':id_aplicacao' => $id_aplicacao,
        ));
        if (!empty($permissao)) {
            $orgaos = self::getHierarquiaDescendenteOrgao($permissao->id_orgao);
        }
        return $orgaos;
    }

    /**
     * Método para manipulação dos órgãos da instituição.
     * 
     * Retorna um array com a hierarquia de órgaos que a pessoa tem chefia.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @return array Hierarquia de órgaos que a pessoa tem chefia
     */
    public static function getHierarquiaOrgaosChefia($id_pessoa)
    {
        $orgaos = array();
        $servidor = DadoFuncional::model()->find(
            'id_pessoa = :id_pessoa
            and coalesce(data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
            and coalesce(data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() ', array(
            ':id_pessoa' => $id_pessoa
            )
        );
        $orgaosChefia = Orgao::model()->findAll('matricula_dirigente = :matricula1 OR matricula_substituto = :matricula2', array(
            ':matricula1' => $servidor->matricula,
            ':matricula2' => $servidor->matricula,
        ));
        foreach ($orgaosChefia as $orgao) {
            $orgaos = array_merge($orgaos, self::getHierarquiaDescendenteOrgao($orgao->id_orgao));
        }
        return $orgaos;
    }

    /**
     * Método para manipulação de órgãos da instituição.
     * 
     * Retorna um array contendo o atributo id_orgao de todos os órgãos em ordem 
     * descendente.
     * 
     * @param int $id_orgao Chave primária do órgão
     * @return array Chaves primárias dos órgaos encontrados em ordem descentende
     */
    public static function getHierarquiaDescendenteOrgao($id_orgao)
    {
        $orgaosDescendentes = array($id_orgao);
        $orgaosInferiores = Orgao::model()->findAll('id_orgao_superior = :id_orgao', array(':id_orgao' => $id_orgao));
        foreach ($orgaosInferiores as $orgao) {
            $orgaosDescendentes = array_merge($orgaosDescendentes, self::getHierarquiaDescendenteOrgao($orgao->id_orgao));
        }
        return $orgaosDescendentes;
    }

    /**
     * Método para manipulação de órgãos da instituição.
     * 
     * Retorna um array contendo o atributo id_orgao de todos os órgãos em ordem 
     * ascendente.
     * 
     * @param int $id_orgao Chave primária do órgão
     * @return array Chaves primárias dos órgaos encontrados em ordem ascendente
     */
    public static function getHierarquiaAscendenteOrgao($id_orgao)
    {
        $orgaosAscendentes = array($id_orgao);
        $orgao = Orgao::model()->findByPk($id_orgao);
        while (trim($orgao->id_orgao_superior) != '') {
            $orgaosAscendentes[] = $orgao->id_orgao_superior;
            $orgao = Orgao::model()->findByPk($orgao->id_orgao_superior);
        }
        return $orgaosAscendentes;
    }

    /**
     * Método para manipulação de texto.
     * 
     * A função analisa os valores passados por parâmetro verficando se o 
     * primeiro valor é diferente de NULL ou ''. Se as condições forem verdadeiras
     * é retornado o primeiro parâmetro, caso contrário é retornado o segundo.
     * 
     * @param mixed $val_1 Valor a ser verificado
     * @param midex $val_2 Valor a ser usado por padrão
     * @return mixed Retorna um dos parâmetros passados para o método
     */
    public static function coalesce($val_1, $val_2)
    {
        $strVal_1 = strval($val_1);
        if ($val_1 != NULL && trim($strVal_1) != '' && trim($strVal_1) != "''") {
            return $val_1;
        } else {
            return $val_2;
        }
    }
}
