<?php

class Repositorio extends CComponent
{
    private static $_extensoesProibidas = array();
    private $_chaveAutenticacao = NULL;
    private $_erro;

    /**
     *
     */
    public function enderecoBase()
    {
        return '';
    }

    /**
     * Esta função gera uma chave de autenticação, de 12 caracteres alfanuméricos,
     * que pode ser incluída no documento gerado (por exemplo, um PDF).
     */
    public function geraChaveAutenticacao()
    {
        $this->_chaveAutenticacao = "";
        /* INICIA COM TRÊS LETRAS MAIÚSCULAS */
        $this->_chaveAutenticacao .= chr(rand(65, 90));
        $this->_chaveAutenticacao .= chr(rand(65, 90));
        $this->_chaveAutenticacao .= chr(rand(65, 90));

        /* SEGUNDOS mod 100 */
        $this->_chaveAutenticacao .= str_pad(time() % 1000, 3, "0", STR_PAD_LEFT);

        /* MILISEGUNDOS */
        $t = gettimeofday();
        $this->_chaveAutenticacao .= str_pad((int) ($t['usec'] / 1000), 3, "0", STR_PAD_LEFT);

        /* TRÊS PRIMEIROS CARACTERES DO SESSION_ID */
        $this->_chaveAutenticacao .= strtoupper(substr(session_id(), 0, 3));

        if (!$this->verificaChaveAutenticacaoNova($this->_chaveAutenticacao))
            $this->geraChaveAutenticacao();

        return true;
    }

    /**
     *
     */
    private function verificaChaveAutenticacaoNova($chave)
    {
        return true;
    }

    /**
     * Devolve a chave de autenticação formatada para exibição ao usuário,
     * no formado XXX.XXX.XXX.XXX . O argumento é opcional, e caso ele seja
     * FALSE, devolverá a chave sem formatação, isto é, sem pontos.
     * @param boolean $Formatada Por default é TRUE.
     * @return string Chave de autenticação
     */
    public function devolveChaveAutenticacao($formatada = true)
    {
        if ($formatada !== false)
            return preg_replace('/^(.{3})(.{3})(.{3})(.{3})$/', '$1.$2.$3.$4', $this->_chaveAutenticacao);
        else
            return $this->_chaveAutenticacao;
    }

    /**
     * Realiza o upload de um arquivo. Caso tenha sido gerada uma chave de autenticação
     * para o objeto, ela será associado ao arquivo e será apagada em seguida.
     * @param int $TipoDocumentoDigital O tipo de documento, segundo a tabela de apoio.
     * @param string $NomeArquivo O nome original do arquivo que se deseja enviar.
     * @param string $ConteudoArquivo O conteúdo binário do arquivo.
     * @param string $tipo_arquivo Tipo MIME do arquivo, ex., application/pdf
     * @return string O identifiador único do arquivo, usado posteriormente para visualização. Em caso de falha, retorna FALSE.
     */
    public function upload($NomeArquivo, $ConteudoArquivo, $tipo_arquivo)
    {
        if (isset(Yii::app()->params['Repositorio']['ExtensoesProibidas']))
            self::$_extensoesProibidas = Yii::app()->params['Repositorio']['ExtensoesProibidas'];

        if (isset(Yii::app()->params['Repositorio']['TamanhoMaximoArquivo']))
            $iTamanhoMaximo = Yii::app()->params['Repositorio']['TamanhoMaximoArquivo'];
        else
            $iTamanhoMaximo = 5;

        if (strlen($ConteudoArquivo) > ($iTamanhoMaximo * 1024 * 1024)) {
            $this->_erro = "Arquivo grande demais. O tamanho máximo permitido é " . $iTamanhoMaximo . " MB";
            return false;
        }

        $explodeVar = explode('.', $NomeArquivo);
        $extensao = end($explodeVar);


        //if(!in_array($extensao, self::$_extensoesPermitidas)) {
        if (in_array($extensao, self::$_extensoesProibidas)) {
            $this->_erro = "Extensão de arquivo não permitida";
            return false;
        }

        $insert = false;
        do {
            $chave = substr(strtoupper(md5(rand())), 1, 12);

            $query = Yii::app()->db->createCommand("select 1 from repositorio where chave_repositorio = :chave");
            $query->bindValue(":chave", $chave);

            if ($query->queryRow() === false) {
                $sql = " insert into repositorio ( chave_repositorio , data_criacao, nome_arquivo)
						values('$chave', CURRENT_TIMESTAMP(), '$NomeArquivo')";

                Yii::app()->db->createCommand($sql)->execute();
                $insert = true;
            }
        }
        while (!$insert);

        $sEndereco = $this->enderecoBase() . '/repositorio/docs/' . $chave;
        $sEnderecoMetadados = $this->enderecoBase() . '/repositorio/docs/' . $chave . '.txt';

        $aMetadados = array(
            'tipo_arquivo' => $tipo_arquivo,
            'nome_arquivo' => $NomeArquivo,
            'chave_autenticacao' => $this->_chaveAutenticacao,
            'chave_repositorio' => $chave,
            'data_expiracao' => '',
        );

        file_put_contents($sEndereco, $ConteudoArquivo);
        file_put_contents($sEnderecoMetadados, serialize($aMetadados));

        if ($this->_chaveAutenticacao != '') {
            $sEnderecoChaves = $this->enderecoBase() . '/repositorio/docs/autenticacao.txt';
            if (is_file($sEnderecoChaves))
                $aChaves = unserialize(file_get_contents($sEnderecoChaves));
            else
                $aChaves = array();

            $aChaves[$this->_chaveAutenticacao] = $chave;
            file_put_contents($sEnderecoChaves, serialize($aChaves));
        }

        return $chave;
    }

    /**
     * 
     * @return type
     */
    public function erro()
    {
        return $this->_erro;
    }

    /**
     * Devolve o link para visualização do arquivo, que inclui o identificador da sessão no repositório
     * Esse método deve sempre ser invocado ao oferecer o link de acesso ao arquivo ao usuário.
     * O link gerado não deve ser salvo em nenhum lugar, pois ele só permite o acesso 
     * ao documento durante o tempo de vida da sessão.
     * @param int $TipoDocumento O tipo de documento, segundo a tabela de apoio.
     * @param string $chave_repositorio A chave de identificação devolvida pelo repositório.
     * @param boolean $Download Caso se deseje imprimir um link para baixar, e não visualizar diretamente o arquivo. Por default é FALSE.
     * @return string O link para visualização, ou FALSE em caso de erro.
     */
    public function devolveLinkExibicao($tipoDocumento, $chaveIdentificacao, $download = false)
    {
        return "http://" . $_SERVER['SERVER_NAME'] . "/repositorio/abreArquivo.php?" . $chaveIdentificacao . "&" . $tipoDocumento;
    }

    /**
     * 
     * @param type $tipoDocumento
     * @param type $chaveIdentificacao
     * @return type
     */
    public function devolveCaminhoAcessoDireto($tipoDocumento, $chaveIdentificacao)
    {
        return "http://" . $_SERVER['SERVER_NAME'] . "/repositorio/abreArquivo.php?" . $chaveIdentificacao . "&" . $tipoDocumento;
    }

    /**
     * Estabelece uma data de fim de validade para um documento digital.
     * 
     * 	
     * @param int $TipoDocumento O tipo de documento, segundo a tabela de apoio.
     * @param string $chave_repositorio A chave de identificação devolvida pelo repositório.
     * @param string $data_expiracao Data de fim do documento (convertida para o formato do banco pela classe ValidatorData)
     * @return boolean True em caso de sucesso. False, insucesso.
     */
    public function insereDataExpiracao($tipoDocumento, $chaveIdentificacao, $dataFimValidade)
    {
        $sEnderecoMetadados = $this->enderecoBase() . '/repositorio/docs/' . $chaveIdentificacao . '.txt';
        $aMetadados = unserialize(file_get_contents($sEnderecoMetadados));
        $aMetadados['data_expiracao'] = $dataFimValidade;
        file_put_contents($sEnderecoMetadados, serialize($aMetadados));

        return true;
    }

    /**
     * Verifica se determinada chave_autenticacao existe e retorna um array a respectiva
     * chave_repositorio e o TipoDocumento associados ao documento.
     * 	@param string $chave_autenticacao 
     * 	@return array|boolean Array com ChaveIdenticacao e TipoDocumento associado ou erro.
     */
    public function verificaChaveAutenticacao($chaveAutenticacao)
    {
        $sEnderecoChaves = $this->enderecoBase() . '/repositorio/docs/autenticacao.txt';
        $aChaves = unserialize(file_get_contents($sEnderecoChaves));

        if (isset($aChaves[$chaveAutenticacao])) {
            $sEnderecoMetadados = $this->enderecoBase() . '/repositorio/docs/' . $aChaves[$chaveAutenticacao] . '.txt';
            $aMetadados = unserialize(file_get_contents($sEnderecoMetadados));

            if ($aMetadados['IndicadorCancelado'] == 'S')
                return false;

            if ($aMetadados['data_expiracao'] != '') {
                $aData = explode('/', $aMetadados['data_expiracao']);
                $iData = intval($aData[2] . $aData[1] . $aData[0]);
                if (date('Ymd') > $iData)
                    return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Devolve o nome original de um arquivo.
     *
     * @param int $TipoDocumento
     * @param string $chave_repositorio
     * @return string|boolean Nome original, ou false caso o arquivo não exista.
     */
    public function devolveNomeArquivo($tipoDocumento, $chaveIdentificacao)
    {
        $sEnderecoMetadados = $this->enderecoBase() . '/repositorio/docs/' . $chaveIdentificacao . '.txt';
        $aMetadados = @unserialize(file_get_contents($sEnderecoMetadados));
        if (empty($aMetadados))
            return false;
        else
            return $aMetadados['nome_arquivo'];
    }

    /**
     * 
     * @param type $tipoDocumento Numérido que identifica o tipo de arquivo
     * @param type $chaveIdentificacao Chave de identificação do arquivo
     * @param type $nomeDocumento Nome do arquivo com sua extensão
     * @return boolean
     */
    public function alteraNomeArquivo($tipoDocumento, $chaveIdentificacao, $nomeDocumento)
    {
        $nomeDocumento = str_replace("'", "''", $nomeDocumento);

        $sEnderecoMetadados = $this->enderecoBase() . '/repositorio/docs/' . $chaveIdentificacao . '.txt';
        $aMetadados = @unserialize(file_get_contents($sEnderecoMetadados));
        if (empty($aMetadados))
            return false;

        $aMetadados['nome_arquivo'] = $nomeDocumento;
        file_put_contents($sEnderecoMetadados, serialize($aMetadados));
    }

}