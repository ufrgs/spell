
$(document).ready(function() {
    
    // Modal do pedido
    $("#divPedido").dialog({title: "Registro de Compensação", width: 650, height: 350, autoOpen:false, modal:true});

    // Modal da certificação
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

/**
 * **compensacao.js**
 * 
 * Função utilizada para preparar e exibir o modal para criação de um novo pedido.
 * Aqui são incluidos os elementos do modal manualmente, dispensando o uso de um
 * arquivo externo contendo o código HTML do modal.
 * 
 * @returns {void} Prepara e exibe o modal para criação de um novo pedido
 */
function novoRegistro() {
    $("#divPedido input[type=text]").val('');
    $("#divPedido").dialog('open');
}

/**
 * **compensacao.js**
 * 
 * Função utilizada para validar os campos do modal de pedido de compensação. 
 * Caso os dados necessários para realizar um pedido tenham sido informados, 
 * a função também solicita o ajuste.
 * 
 * @returns {void} Mostra na tela mensagem de erro ou sucesso na validação dos campos e da solicitação
 */
function enviaSolicitacao() {
    var msg = "";
    if ($("#data").val() == "") {
        msg += "Selecione o dia para o ajuste. <br/>";
    }
    else {
        // testa se e um dia valido
        var auxData = $("#data").val().split("/");
        auxData = auxData[2] + "/" + auxData[1] + "/" + auxData[0];
        if (!Date.parse(auxData)) {
            msg += "Escreva um dia válido. <br/>";
        }
        else {
            // testa se o dia e menor que o atual
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
        // testa se e um horario valido
        if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($("#hora").val())) {
            msg += "Escreva um horário válido. <br/>";
        }
    }
    // verifica se nao esta pedindo mais compensacao do que tem saldo de horas
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
        $("#botaoEnviar").html('<img src="/ponto/css/imgs/smallLoader.gif"/> Enviando...');
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

/**
 * **compensacao.js**
 * 
 * Função para exclusão de um pedido de compensação.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @returns {void} Atualiza a tela em caso de sucesso ou exibe mensagem de erro
 */
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

/**
 * **compensacao.js**
 * 
 * Função para exibir a barra de pregresso de upload dos anexos utilizados no
 * pedido de compensação.
 * 
 * @param {Event} e Evento a ser aplicado a animação
 * @returns {void} Mostra e aplica animação na barra de progresso
 */
function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}

// Métodos utilizados para certificação

/**
 * **compensacao.js**
 * 
 * Função utilizada para mostrar detalhes de um pedido de ajuste. Quando chamada
 * um modal é aberto com as informações do pedido.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @param {string} tipo Tipo do pedido. Na tela de ajustes tem o valor "ajuste"
 * @returns {void} Mostra um modal com o pedido solicitado ou uma mensagem de erro
 */
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

/**
 * **compensacao.js**
 * 
 * Função utilizada para mostrar detalhes de um pedido de ajuste já certificado. 
 * Quando chamada um modal é aberto com as informações do pedido certificado.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @param {string} tipo Tipo do pedido. Na tela de ajustes tem o valor "ajuste"
 * @returns {void} Mostra um modal com o pedido solicitado ou uma mensagem de erro
 */
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

/**
 * **compensacao.js**
 * 
 * Função utilizada para certificação de um único pedido de compensação. Quando 
 * essa função é chamada, o identificador do pedido de compensação solicitado é 
 * enviado ao servidor contendo seu novo estado (aprovado ou negado) juntamente 
 * com a justificativa do certificador.
 * 
 * @param {char} certifica S ou N indicando a aprovação ou reprovação do pedido de ajuste
 * @returns {void} Atualiza a tela em caso de sucesso ou exibe mensagem de erro
 */
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

/**
 * **compensacao.js**
 * 
 * Função utilizada para certificação de mais de um pedido de compensação. Quando 
 * essa função é chamada, os pedidos de compensação ainda não certificados são
 * enviados para o servidor com seu estado alterado para aprovado.
 * 
 * @returns {void} Atualiza a tela em caso de sucesso ou exibe mensagem de erro
 */
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