$(document).ready(function() {
    $("#modal").dialog({title: "Informações do Pedido", autoOpen: false, width: 600, height: 450});
});

/**
 * **pedidosAvaliacao.js**
 * 
 * Função utilizada para mostrar detalhes de um pedido de ajuste. Quando chamada
 * um modal é aberto com as informações do pedido.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @param {string} tipo Tipo do pedido. Na tela de ajustes tem o valor "ajuste"
 * @returns {void} Mostra um modal com o pedido solicitado ou uma mensagem de erro
 */
function verPedido(nr, tipo) {
    $.ajax({
        url: HOME+"ajuste/dadosPedido",
        type: 'POST',
        data: {'nr': nr, 'tipo': tipo},
        success: function(retorno) {
            $("#modal").html(retorno);
            $("#modal").dialog('open');
            $("#justificativa").focus();
        },
        error: function(retorno) {
            alert(retorno);
        }
    });
}

/**
 * **pedidosAvaliacao.js**
 * 
 * Método utilizada para certificar um pedido de ajuste. Ao ser chamada, o método
 * faz uma requisição ao servidor contendo o tipo de pedido e o estado dele.
 * 
 * @param {char} certifica S ou N indicando a aprovação ou reprovação do pedido de ajuste
 * @param {String} tipo Tipo do ajuste solicitado.
 * @returns {void} Mostra uma mensagem de sucesso ou erro após a tentativa de certificação
 */
function certificarPedido(certifica, tipo) {
    if ($("#nrAjuste").val() != '') {
        if ((certifica == 'N') && ($("#justificativa").val().length < 3)) {
            $("#lblJustificativa").html("justificativa não pode ser vazia").addClass('textoVermelho');
            $("#justificativa").css('border', '1px solid #c00');
        }
        else {
            $.ajax({
                url: HOME+"ajuste/certificaPedido",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    nrPedido: $("#nrPedido").val(),
                    tipo: tipo,
                    certifica: certifica,
                    justificativa: $("#justificativa").val(),
                },
                success: function(retorno) {
                    $("#modal").html('<fieldset class="field'+(retorno.erro ? 'Erro' : 'Sucesso')+'">'+retorno.mensagem+'</fieldset>');
                    if (retorno.mensagem.indexOf("sucesso") != -1) {
                        setTimeout(function(){window.location=HOME+"ajuste/pedidosAvaliacao/"}, 2000);
                    }
                },
                error: function(retorno) {
                    alert(retorno);
                }
            });
        }
    }
}

/**
 * **pedidosAvaliacao.js**
 * 
 * Função utilizada para certificar vários pedidos de uma vez só. Ao ser chamada, 
 * o método faz uma requisição ao servidor contendo os tipos e estados de todos
 * os pedidos ainda não certificados que estejam visiveis na tela do usuário.
 * 
 * @param {String} tipo Tipo do ajuste solicitado.
 * @returns {void} Mostra uma mensagem de sucesso ou erro após a tentativa de certificação
 */
function certificarSelecionados(tipo) {
    var pedidos = $("input[name=certificar"+tipo+"]:checked");
    if (pedidos.length > 0) {
        var certificar = {};
        $(pedidos).each(function(i, pedido) {
            certificar[i] = $(pedido).val();
        });
        $.ajax({
            url: HOME+"ajuste/certificaVarios",
            type: 'POST',
            dataType: 'JSON',
            data: {
                pedidos: certificar,
                tipo: tipo,
            },
            success: function(retorno) {
                $("#modal").html('<fieldset class="field'+(retorno.erro ? 'Erro' : 'Sucesso')+'">'+retorno.mensagem+'</fieldset>');
                $("#modal").dialog('open');
                if (retorno.mensagem.indexOf("sucesso") != -1) {
                    setTimeout(function(){window.location=HOME+"ajuste/pedidosAvaliacao/"}, 2000);
                }
            },
            error: function(retorno) {
                alert(retorno);
            }
        });
    }
}