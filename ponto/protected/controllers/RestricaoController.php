<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para permitir a adição de restrições de IP.
 * 
 * O código necessário para controlar o acesso de um órgão ou servidor ao
 * sistema de ponto eletrônico com base em seu endereço de IP é implementado 
 * nos métodos desse controlador.
 * 
 * Aqui pode ser feita a busca de servidores e órgão e a adição e exclusão de 
 * restrições.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class RestricaoController extends BaseController
{

    /**
     * Action utilizada para listagem das restrições existêntes.
     * 
     * Esse método monta a tela com as listas de restrições separadas por órgão
     * e servidor usando o método <code>render()</code>.
     */
    public function actionIndex()
    {
        $restricoesOrgao = new RestricaoRelogio('search');
        $restricoesPessoa = new RestricaoRelogio('search');
        $restricoesPessoa->porOrgao = false;
        if (isset($_GET['RestricaoRelogio'])) {
            $restricoesOrgao->attributes = $_GET['RestricaoRelogio']; 
            if (isset($_GET['RestricaoRelogio']['sigla_orgao'])) {
                $restricoesOrgao->sigla_orgao = strtoupper($_GET['RestricaoRelogio']['sigla_orgao']);
            }
            $restricoesPessoa->attributes = $_GET['RestricaoRelogio']; 
            if (isset($_GET['RestricaoRelogio']['nome_pessoa'])) {
                $restricoesPessoa->nome_pessoa = $_GET['RestricaoRelogio']['nome_pessoa'];
            }
        }

        $this->render("index", array(
            'restricoesOrgao' => $restricoesOrgao,
            'restricoesPessoa' => $restricoesPessoa,
        ));
    }
    
    /**
     * Action utilizada para salvar uma restrição.
     * 
     * Esse método pode tanto alterar uma restrição ou criar uma nova.
     * 
     * Esta action precisa receber via método POST os seguintes parâmetros: 
     *    - CodRestricao: Chave primária da classe {@see RestricaoRelogio}
     *    - id_orgao: Chave primária da classe {@see Orgao}
     *    - id_pessoa: Chave primário da classe {@see Pessoa}
     *    - ipv4: IP restrito no formato IPv4
     *    - ipv6: IP restrito no formato IPv6
     * 
     * Para que uma restrição seja criada o parâmetro CodRestricao deve não
     * ser enviado ou conter valor zero. Caso esta condição não seja cumprida o
     * método fará uma alteração em uma restrição existente.
     * 
     * Um elemento fieldset é retornado usando a instrução <code>print</code>
     * contendo mensagem e classes indicando sucesso ou fracasso na operação
     * executado pela action.
     */
    public function actionSalvar() 
    {
        if (isset($_POST['CodRestricao']) && $_POST['CodRestricao'] != 0) { // alteracao
            $restricao = RestricaoRelogio::model()->findByPk($_POST['CodRestricao']);
        }
        else { // inclusao
            $restricao = new RestricaoRelogio();
            $restricao->id_orgao = $_POST['id_orgao'] ?: NULL;
            if (trim($_POST['id_pessoa']) != '') {
                $dados = explode("|", $_POST['id_pessoa']);
                $restricao->id_pessoa = $dados[0];
                $restricao->matricula = $dados[1];
                $restricao->nr_vinculo = $dados[2];
            }
        }
        $restricao->mascara_ip_v4 = $_POST['ipv4'] ?: NULL;
        $restricao->mascara_ip_v6 = $_POST['ipv6'] ?: NULL;
        $restricao->data_atualizacao = new CDbExpression("CURRENT_TIMESTAMP()");
        $restricao->id_pessoa_atualizacao = Yii::app()->session['id_pessoa'];
        $restricao->ip_atualizacao = $_SERVER['REMOTE_ADDR'];
        
        if ($restricao->save()) {
            print '<fieldset class="fieldSucesso">Restrição salva com sucesso!</fieldset>';
        }
        else {
            print '<fieldset class="fieldErro">Ocorreu um erro ao salvar a restrição.'.print_r($restricao->getErrors(), true).'</fieldset>';
        }
    }
    
    /**
     * Action utilizada para excluir uma restrição.
     * 
     * Essa action procura uma restrição que contenha a chave primária igual ao
     * valor do parâmetro "nr" passado via método POST.
     * 
     * Um elemento fieldset é retornado usando a instrução <code>print</code>
     * contendo mensagem e classes indicando sucesso ou fracasso na operação
     * executado pela action.
     */
    public function actionExcluir() 
    {
        if (isset($_POST['nr'])) { 
            $restricao = RestricaoRelogio::model()->findByPk($_POST['nr']);
        
            if ($restricao->delete()) {
                print '<fieldset class="fieldSucesso">Restrição excluída com sucesso!</fieldset>';
            }
            else {
                print '<fieldset class="fieldErro">Ocorreu um erro ao excluir a restrição.'.print_r($restricao->getErrors(), true).'</fieldset>';
            }
        }
    }

    /**
     * Action utilizada para pesquisa de órgãos.
     * 
     * O método recebe um parâmetro com o termo a ser comparado com o nome e 
     * com o código do órgão usando o comando LIKE da linguaguem SQL.
     * 
     * Os resultadores encontrados serão devolvidos em formato JSON seguindo o
     * exemplo:
     * 
     * <code>
     * {
     *  "id": 0,
     *  "label": "0 - Nome",
     *  "text": "0 - Nome"
     * }
     * </code>
     * 
     * @param string $term Texto a ser usado na comparação com o nome e o id
     */
    public function actionOrgaos($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $orgaos = Orgao::model()->findAll("nome_orgao like '%$term%' COLLATE utf8_general_ci or upper(sigla_orgao) like '%$term%'");

        $opcoes = array();
        if (!empty($orgaos)) {
            foreach ($orgaos as $orgao) {
                $opcoes[] = array(
                    'id' => $orgao->id_orgao,
                    'label' => $orgao->sigla_orgao." - ".$orgao->nome_orgao,
                    'text' => $orgao->sigla_orgao." - ".$orgao->nome_orgao
                );
            }
        }
        else {
            $opcoes[] = array(
                'id' => '',
                'label' => 'Nenhum órgão encontrado',
                'text' => 'Nenhum órgão encontrado'
            );
        }

        print CJSON::encode($opcoes);
        Yii::app()->end();
    }

    /**
     * Action utilizada para pesquisa de pessoas.
     * 
     * O método recebe um parâmetro com o termo a ser comparado com o nome e 
     * com o código da pessoa usando o comando LIKE da linguaguem SQL.
     * 
     * Os resultadores encontrados serão devolvidos em formato JSON seguindo o
     * exemplo:
     * 
     * <code>
     * {
     *  "id": 0,
     *  "label": "0 - Nome",
     *  "text": "0 - Nome"
     * }
     * </code>
     * 
     * @param string $term Texto a ser usado na comparação com o nome e o id
     */
    public function actionPessoas($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $pessoas = Pessoa::model()->with('DadoFuncional')->findAll("
            coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
            and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
            and (t.nome_pessoa like '%$term%'  COLLATE utf8_general_ci or LTRIM(CAST(t.id_pessoa as char(12))) = '$term')");

        $opcoes = array();
        if (!empty($pessoas)) {
            foreach ($pessoas as $pessoa) {
                $opcoes[] = array(
                    'id' => $pessoa->id_pessoa."|".$pessoa->DadoFuncional->matricula."|".$pessoa->DadoFuncional->nr_vinculo,
                    'label' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa,
                    'text' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa
                );
            }
        }
        else {
            $opcoes[] = array(
                'id' => '',
                'label' => 'Nenhuma pessoa encontrada',
                'text' => 'Nenhuma pessoa encontrada'
            );
        }

        print CJSON::encode($opcoes);
        Yii::app()->end();
    }
}