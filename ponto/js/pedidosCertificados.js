$(document).ready(function() {
    $("#modal").dialog({title: "Informações do Pedido", autoOpen: false, width: 600, height: 450});
});

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