$(document).ready(function () {
    
    /**
     * **restricao.js**
     * 
     * Função que implementa o recurso de autocompletar no campo de pesquisa
     * de órgãos da tela de restrições.
     * 
     * @ignore
     * @event Evento de autocompletar do jQuery.
     * @link https://jqueryui.com/autocomplete/
     */
    $("#acOrgaos").autocomplete({
        source: HOME + "restricao/orgaos",
        minLength: 3,
        delay: 500,
        select: function (event, ui) {
            $("#id_orgao").val(ui.item.id);
            $("#nomeRestricao").html(ui.item.value);
            $("#modal").dialog("open");
        }
    });

    /**
     * **restricao.js**
     * 
     * Função que implementa o recurso de autocompletar no campo de pesquisa
     * de servidores da tela de restrições.
     * 
     * @ignore
     * @event Evento de autocompletar do jQuery.
     * @link https://jqueryui.com/autocomplete/
     */
    $("#acPessoas").autocomplete({
        source: HOME + "restricao/pessoas",
        minLength: 3,
        delay: 500,
        select: function (event, ui) {
            $("#id_pessoa").val(ui.item.id);
            $("#nomeRestricao").html(ui.item.value);
            $("#modal").dialog("open");
        }
    });
    $("#modal").dialog({width: 500, height: 200, title: "Restrição de IPs", autoOpen: false});
});

/**
 * **restricao.js**
 * 
 * Função utilizada para permitir a alteração nos endereços de IP de um restrição
 * existente. A função abre um modal mostrando os informações da restrição e os 
 * campos para preenchimento dos novos endereços de IP no formato IPv4 e IPv6.
 * 
 * @param {int} nr Número da restrição
 * @param {string} nome Nome do órgão ao qual essa restrição se aplica
 * @param {string} ipv4 Endereço IP a ter a restrição no formato IPv4
 * @param {string} ipv6 Endereço IP a ter a restrição no formato IPv6
 * @returns {void} Mostra um modal contendo os campos que podem ser alterados
 */
function alteraRestricao(nr, nome, ipv4, ipv6) {
    $("#CodRestricao").val(nr);
    $("#nomeRestricao").html(nome);
    $("#ipv4").val(ipv4);
    $("#ipv6").val(ipv6);
    $("#modal").dialog("open");
}

/**
 * **restricao.js**
 * 
 * Função utilizada para salvar os dados de uma restrição modificada. Após a
 * chamada da função alteraRestricao, que abre o modal com os campos para 
 * alteração, os dados preenchidos são enviados para o servidor para que a alteração
 * seja aplicada.
 * 
 * @returns {void} Mostra um modal contendo os campos que podem ser alterados
 */
function salvarRestricao() {
    if (($("#CodRestricao").val() != "") || ($("#id_orgao").val() != "") || ($("#id_pessoa").val() != "")) {
        $.ajax({
            type: 'POST',
            url: HOME + 'restricao/salvar',
            data: $("#formRestricao").serialize(),
            success: function (result) {
                $("#modal").html(result);
                if (result.indexOf("sucesso") != -1)
                    document.location.reload(true);
            },
            error: function (result) {
                $("#modal").html(result);
            }
        });
    }
    else {
        alert("?");
    }
}

/**
 * **restricao.js**
 * 
 * Função utilizada para remover uma restrição criada. É feita uma requisição
 * ao servidor informando o número único da restrição a ser apagada.
 * 
 * @param {int} nr Número da restrição.
 * @returns {void} Atualiza a tela em caso de sucesso ou exibe mensagem de erro
 */
function excluiRestricao(nr) {
    if (confirm("Tem certeza que deseja exluir essa restrição?")) {
        $.ajax({
            type: 'POST',
            url: HOME + 'restricao/excluir',
            data: { nr: nr },
            success: function (result) {
                $("#modal").html(result).dialog('open');
                if (result.indexOf("sucesso") != -1)
                    document.location.reload(true);
            },
            error: function (result) {
                $("#modal").html(result).dialog('open');
            }
        });
    }
}