<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Componente para representar o repositório de documentos
 * 
 * Aqui são definidos métodos para manipulação dos documentos utlizados na
 * aplicação, por exemplo, os documentos que podem ser anexados às mudanças de 
 * horários.
 * 
 * Caso um funcionário precise se ausentar por problemas médicos, por exemplo, 
 * ao solicitar a alteração no horário o atestado médico em formato PDF pode 
 * ser enexado ao pedido.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class Repositorio extends CComponent
{

    /**
     * Lista de extensões de arquivos que não são aceitas
     * 
     * @var array 
     */
    private static $_extensoesProibidas = array();
    
    /**
     * Chave de autenticação do arquivo atual
     * 
     * @var string 
     */
    private $_chaveAutenticacao = NULL;
    
    /**
     * Mensagem de erro a ser retornada por algum método
     * 
     * @var string 
     */
    private $_erro;

    /**
     * Método para busca do diretório base dos arquivos
     * 
     * Função crida para reaproveitamento de código.
     * 
     * @return String Diretório base do sistema
     */
    public function enderecoBase()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '';
    }

    /**
     * Método para autenticação de documentos
     * 
     * Esta função gera uma chave de autenticação, de 12 caracteres alfanuméricos,
     * que pode ser incluída no documento gerado (por exemplo, um PDF).
     * 
     * @return boolean Retorna TRUE indicando sucesso na geração da chave
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

        if (!$this->verificaChaveAutenticacaoNova($this->_chaveAutenticacao)) {
            $this->geraChaveAutenticacao();
        }

        return true;
    }

    /**
     * Método para autenticação de documentos
     * 
     * Essa função verifica a autenticidade de uma chave gerada pelo método
     * <code>Repositorio::geraChaveAutenticacao()</code>.
     * 
     * @param string $chave Chave alfanumérica a ser validada 
     * @return boolean Retorna TRUE indicando autenticidade da chave
     */
    private function verificaChaveAutenticacaoNova($chave)
    {
        return true;
    }

    /**
     * Método auxiliar para a autenticação de documentos
     * 
     * Devolve a chave de autenticação formatada para exibição ao usuário
     * no formado XXX.XXX.XXX.XXX. O argumento é opcional, e caso ele seja
     * FALSE, devolverá a chave sem formatação, isto é, sem pontos.
     * 
     * @param boolean $formatada Indicador de formação da chave
     * @return string Retorna a chave de autenticação
     */
    public function devolveChaveAutenticacao($formatada = true)
    {
        if ($formatada !== false) {
            return preg_replace('/^(.{3})(.{3})(.{3})(.{3})$/', '$1.$2.$3.$4', $this->_chaveAutenticacao);
        } else {
            return $this->_chaveAutenticacao;
        }
    }

    /**
     * Método para realizar o envio de arquivos para o servidor
     * 
     * Caso tenha sido gerada uma chave de autenticação para o objeto ela será 
     * associada ao arquivo e será apagada em seguida.
     * 
     * @param string $NomeArquivo Nome a ser usado para armazenar o arquivo
     * @param string $ConteudoArquivo Conteúdo do arquivo a ser armazenado
     * @param char $tipo_arquivo Tipo da alteração que está sendo feita (Ajuste ou Abono)
     * @return boolean|string Retorna false em caso de erro ou a chave de autenticidade do arquivo
     */
    public function upload($NomeArquivo, $ConteudoArquivo, $tipo_arquivo)
    {
        if (isset(Yii::app()->params['Repositorio']['ExtensoesProibidas'])) {
            self::$_extensoesProibidas = Yii::app()->params['Repositorio']['ExtensoesProibidas'];
        }
        
        if (isset(Yii::app()->params['Repositorio']['TamanhoMaximoArquivo'])) {
            $iTamanhoMaximo = Yii::app()->params['Repositorio']['TamanhoMaximoArquivo'];
        } else {
            $iTamanhoMaximo = 5;
        }
        
        if (strlen($ConteudoArquivo) > ($iTamanhoMaximo * 1024 * 1024)) {
            $this->_erro = "Arquivo grande demais. O tamanho máximo permitido é " . $iTamanhoMaximo . " MB";
            return false;
        }

        $explodeVar = explode('.', $NomeArquivo);
        $extensao = end($explodeVar);

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
        } while (!$insert);

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
     * Método auxiliar para acesso ao atributo _erro
     * 
     * @return string Mensagem de erro
     */
    public function erro()
    {
        return $this->_erro;
    }

    /**
     * Método para consumo de arquivos
     * 
     * Devolve o link para visualização do arquivo, que inclui o identificador 
     * da sessão no repositório.
     * 
     * Esse método deve sempre ser invocado ao oferecer o link de acesso ao 
     * arquivo ao usuário. O link gerado não deve ser salvo em nenhum lugar, pois 
     * ele só permite o acesso ao documento durante o tempo de vida da sessão.
     * 
     * @param int $tipoDocumento Chave primária do documento
     * @param string $chaveIdentificacao Chave de autenticação do documento
     * @param boolean $download Variável para indicar se o documento será baixado
     * @return string Link temporário para visualização do documento
     */
    public function devolveLinkExibicao($tipoDocumento, $chaveIdentificacao, $download = false)
    {
        return "http://".$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']."/repositorio/abreArquivo.php?" . $chaveIdentificacao . "&" . $tipoDocumento;
    }

    /**
     * Método para a manipulação de arquivos
     * 
     * Estabelece uma data de fim de validade para um documento digital.
     * 	
     * @param int $tipoDocumento Chave primária do documento
     * @param string $chaveIdentificacao Chave de autenticação do documento
     * @param string $dataFimValidade Data de fim do documento
     * @return boolean Retorna TRUE em caso de sucesso
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
     * Método autenticação de documentos
     * 
     * Função para verificar se o documento tem uma chave válida associada.
     * 
     * @param string $chaveAutenticacao Chave de autenticação do documento
     * @return boolean Retorna TRUE caso tenha uma chave válida e FALSE caso contrário
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
                if (date('Ymd') > $iData) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Método para manipulação de documentos
     * 
     * Função para buscar o nome original de um arquivo.
     * 
     * @param int $tipoDocumento Chave primária do documento
     * @param string $chaveIdentificacao Chave de autenticação do documento
     * @return boolean|string Retorna o nome do arquivo ou FALSE caso o arquivo não exista
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
     * Método para manipulação de documentos
     * 
     * Função para alterar o nome original de um arquivo.
     * 
     * @param int $tipoDocumento Chave primária do documento
     * @param string $chaveIdentificacao Chave de autenticação do documento
     * @param string $nomeDocumento Novo nome a ser utilizado no documento
     * @return boolean Retorna FALSE caso o arquivo a ser alterado não exista
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
