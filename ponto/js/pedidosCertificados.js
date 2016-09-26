$(document).ready(function() {
    $("#modal").dialog({title: "Informações do Pedido", autoOpen: false, width: 600, height: 450});
});

/**
 * **pedidosCertificados.js**
 * 
 * Função utilizada para mostrar detalhes de um pedido de ajuste já certificado. 
 * Quando chamada um modal é aberto com as informações do pedido.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @param {string} tipo Tipo do pedido. Na tela de ajustes tem o valor "ajuste"
 * @returns {void} Mostra um modal com o pedido solicitado ou uma mensagem de erro
 */
function verPedido(nr, tipo) {
    $.ajax({
        url: HOME+"ajuste/dadosPedidoCertificado",
        type: 'POST',
        data: {'nr': nr, 'tipo': tipo},
        success: function(retorno) {
            $("#modal").html(retorno);
            $("#modal").dialog('open');
        },
        error: function(retorno) {
            alert(retorno);
        }
    });
}