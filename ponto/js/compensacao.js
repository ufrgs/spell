
$(document).ready(function() {
    // modal do pedido
    $("#divPedido").dialog({title: "Registro de Compensação", width: 650, height: 350, autoOpen:false, modal:true});
    // modal da certificacao
    $("#modal").dialog({title: "Informações do Pedido", autoOpen: false, width: 600, height: 450});
    
    $("#data").datepicker({dateFormat:"dd/mm/yy", minDate: 0, stepMonths: 0, changeMonth: false,});
    $("#data").mask("99/99/9999");
    $("#hora").mask("99:99");
    
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'compensacao/pedido/?v='+$(this).val();
        }
    });
});

function novoRegistro() {
    $("#divPedido input[type=text]").val('');
    $("#divPedido").dialog('open');
}

function enviaSolicitacao() {
    var msg = "";
    if ($("#data").val() == "") {
        msg += "Selecione o dia para o ajuste. <br/>";
    }
    else {
        // testa se é um dia válido
        var auxData = $("#data").val().split("/");
        auxData = auxData[2] + "/" + auxData[1] + "/" + auxData[0];
        if (!Date.parse(auxData)) {
            msg += "Escreva um dia válido. <br/>";
        }
        else {
            // testa se o dia é menor que o atual
            var hoje = new Date();
            hoje.setHours(0,0,0,0);
            auxData = new Date(auxData);
            if (auxData < hoje) {
                msg += "A compensação não pode ser registrada para uma data que já passou. <br/>";
            }
        }
    }
    if ($("#hora").val() == "") {
        msg += "Selecione o tempo (horas) de compensação. <br/>";
    }
    else {
        // testa se é um horário válido
        if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($("#hora").val())) {
            msg += "Escreva um horário válido. <br/>";
        }
    }
    // verifica se não está pedindo mais compensação do que tem saldo de horas
    var minutos = $("#hora").val().split(":");
    minutos = parseInt(minutos[0]*60) + parseInt(minutos[1]);
    if (minutos > $("#saldoMinutos").val()) {
        msg += "O tempo selecionado é maior do que o saldo de horas ainda não utilizado ("+$("#saldoFormatado").val()+"). <br/>";
    }
    if ($("#justificativa").val() == "") {
        msg += "Justifique o seu registro de compensação. <br/>";
    }
    if ($("#justificativa").val().length > 512) {
        msg += "Digite no máximo 512 caracteres na justificativa (atualmente "+$("#justificativa").val().length+")."
    }
    if (msg != "") {
        $("#mensagens").html(msg).slideDown();
    }
    else {
        var formData = new FormData($('form')[0]);
        $("#botaoEnviar").html('<img src="/Design/visual_ufrgs/smallLoader.gif"/> Enviando...');
        $("progress").css('width', '100%').show();
        $.ajax({
            type: 'POST',
            url: HOME+'/compensacao/enviarPedido',
            dataType: 'JSON',
            xhr: function() {  // Custom XMLHttpRequest
                 var myXhr = $.ajaxSettings.xhr();
                 if(myXhr.upload){ // Check if upload property exists
                     myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
                 }
                 return myXhr;
            },
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false,
            success: function(retorno) {
                $("#botaoEnviar").html(retorno.mensagem);
                if (retorno.mensagem.indexOf("sucesso") != -1) {
                    $("#botaoEnviar").addClass("fieldSucesso").slideDown();
                    setTimeout(function(){window.location=HOME+"compensacao/pedido/"}, 2000);
                }
                else {
                    $("#botaoEnviar").addClass("fieldErro").slideDown();
                    $("#botaoEnviar").append('<input type="button" value="Enviar" onclick="enviaSolicitacao()"/>');
                }
            },
            error: function(retorno) {
                $("#botaoEnviar").addClass("fieldErro");
                $("#botaoEnviar").html(retorno).slideDown();
            }
        });
    }
}

function excluir(nr) {
    if (confirm("Tem certeza que deseja excluir esse registro de compensação?")) {
        $.ajax({
            type: 'POST',
            url: HOME + 'compensacao/excluirPedido',
            data: { nr: nr },
            success: function (result) {
                alert(result);
                if (result.indexOf("sucesso") != -1)
                    document.location.reload(true);
            },
            error: function (result) {
                alert(result);
            }
        });
    }
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}

// certificacao

function verPedido(nr) {
    $.ajax({
        url: HOME+"compensacao/dadosPedido",
        type: 'POST',
        data: {'nr': nr},
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

function verPedidoCertificado(nr) {
    $.ajax({
        url: HOME+"compensacao/dadosPedidoCertificado",
        type: 'POST',
        data: {'nr': nr},
        success: function(retorno) {
            $("#modal").html(retorno);
            $("#modal").dialog('open');
        },
        error: function(retorno) {
            alert(retorno);
        }
    });
}

function certificarPedido(certifica) {
    if ($("#nrPedido").val() != '') {
        if ((certifica == 'N') && ($("#justificativa").val().length < 3)) {
            $("#lblJustificativa").html("justificativa não pode ser vazia").addClass('textoVermelho');
            $("#justificativa").css('border', '1px solid #c00');
        }
        else {
            $.ajax({
                url: HOME+"compensacao/certificaPedido",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    nrPedido: $("#nrPedido").val(),
                    certifica: certifica,
                    justificativa: $("#justificativa").val(),
                },
                success: function(retorno) {
                    $("#modal").html('<fieldset class="field'+(retorno.erro ? 'Erro' : 'Sucesso')+'">'+retorno.mensagem+'</fieldset>');
                    if (retorno.mensagem.indexOf("sucesso") != -1) {
                        setTimeout(function(){window.location=HOME+"compensacao/pedidosAvaliacao/"}, 2000);
                    }
                },
                error: function(retorno) {
                    alert(retorno);
                }
            });
        }
    }
}

function certificarSelecionados() {
    var pedidos = $("input[name=certificarCompensacao]:checked");
    if (pedidos.length > 0) {
        var certificar = {};
        $(pedidos).each(function(i, pedido) {
            certificar[i] = $(pedido).val();
        });
        $.ajax({
            url: HOME+"compensacao/certificaVarios",
            type: 'POST',
            dataType: 'JSON',
            data: {
                pedidos: certificar,
            },
            success: function(retorno) {
                $("#modal").html('<fieldset class="field'+(retorno.erro ? 'Erro' : 'Sucesso')+'">'+retorno.mensagem+'</fieldset>');
                $("#modal").dialog('open');
                if (retorno.mensagem.indexOf("sucesso") != -1) {
                    setTimeout(function(){window.location=HOME+"compensacao/pedidosAvaliacao/"}, 2000);
                }
            },
            error: function(retorno) {
                alert(retorno);
            }
        });
    }
}