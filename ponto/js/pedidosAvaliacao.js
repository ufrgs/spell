$(document).ready(function() {
    $("#modal").dialog({title: "Informações do Pedido", autoOpen: false, width: 600, height: 450});
});

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