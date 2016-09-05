<?php

class RestricaoController extends BaseController
{

    public function actionIndex()
    {
        $restricao = new RestricaoRelogio('search');
        $restricoesOrgao = $restricao->search();
        $restricao->porOrgao = false;
        $restricoesPessoa = $restricao->search();

        $this->render("index", array(
            'restricoesOrgao' => $restricoesOrgao,
            'restricoesPessoa' => $restricoesPessoa,
        ));
    }
    
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

    public function actionPessoas($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $pessoas = Pessoa::model()->with('DadoFuncional')->findAll("
            coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
            and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
            and (t.nome_pessoa like '%$term%' or convert(varchar(6), t.id_pessoa) = '$term')");

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