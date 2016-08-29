<?php

class Helper {
    
    
    public static function HorarioOrgao($horario)
    {
        if ($horario == NULL)
        {
            echo "Não cadastrado";
        }
        else
        {
            echo $horario;
        }
    }
    
    /**
		Remove acento de texto utilizando iConv PHP
		@param string $string texto para remover acento
	*/
	public static function tiraAcento($string) {
		//$string = iconv('ISO-8859-1', 'ASCII//TRANSLIT', $string);
		$string = iconv(mb_detect_encoding($string, mb_detect_order(),TRUE), 'ASCII//TRANSLIT', $string);
		$string = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $string);
		return $string;
	}
    
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

    public static function diaDeHoje() {
        return self::diaFormatado(time());
    }

    public static function diaFormatado($dia, $comAno = false) {
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

        return $diaSemana.", ".date("d", $dia)." de ".$mes.($comAno ? " de ".date("Y", $dia) : '');
    }
    
    /**
     * Verifica se um ip pertence a uma subrede (range)
     * @param string $ip
     * @param string $range
     * @return boolean
     */
    public static function ip_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }
    
    /**
     * Retorna um array com a hierarquia de órgaos que libera a id_aplicacao para a pessoa
     * @param int $id_pessoa
     * @param int $id_aplicacao
     * @return array
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
     * Retorna um array com a hierarquia de órgaos que a pessoa tem chefia
     * @param int $id_pessoa
     * @return array
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
     * Retorna um array com os id_orgao descendentes 
     * @param int $id_orgao
     * @return array
     */
    public static function getHierarquiaDescendenteOrgao($id_orgao)
    {
        $orgaosDescendentes = array($id_orgao);
        $orgaosInferiores = Orgao::model()->findAll('id_orgao_superior = :id_orgao', array(':id_orgao' => $id_orgao));
        foreach ($orgaosInferiores as $orgao) {
            array_merge($orgaosDescendentes, self::getHierarquiaDescendenteOrgao($orgao->id_orgao));
        }
        return $orgaosDescendentes;
    }
    
    /**
     * Retorna um array com os id_orgao ascendentes
     * @param int $id_orgao
     * @return array
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
     * returns $val_1 in case $val_1 is different from NULL or ''. Otherwise, returns $val_2
     * @param mixed $val_1
     * @param midex $val_2
     * @return boolean
     */
    public static function coalesce($val_1, $val_2)
    {
        $strVal_1 = strval($val_1);
        if ($val_1 != NULL && trim($strVal_1) != '' && trim($strVal_1) != "''")
            return $val_1;
        else
            return $val_2;
    }
}
