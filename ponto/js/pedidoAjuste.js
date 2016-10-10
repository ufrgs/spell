
$(document).ready(function() {
    $("#divPedido").dialog({title: "Pedido de ajuste", width: 700, height: 350, autoOpen:false, modal:true});
    if ($("#nrPonto").val() != '') {
        // se e correcao de registro, nao mostra abono e periodo
        $("label[for=tipoA]").hide();
        $("#tipoA").hide();
        $("label[for=tipoP]").hide();
        $("#tipoP").hide();
        $("#divPedido").dialog('open');
        if ($("#nrPonto").val() == 0) {
            var aux = $("#tipoEDataAjuste").val().split("|");
            $("#tipo"+aux[0]).prop('checked', true);
            $("#data").val(aux[1]);
            $("#hora").focus();
        }
        else {
            $("#justificativa").focus();
        }
    }
    
    $("input[name=tipo]").change(function() {
        var tipo = $("input[name=tipo]:checked").val();
        $("#divHoraSaida").slideUp();
        $("#divHorasAbono").slideUp();
        $("#lblHora").html("Hora:");
        $(".opt_A").prop('disabled', false).show();
        $(".opt_T").prop('disabled', true).hide();
        $(".opt_P").prop('disabled', true).hide();
        if (tipo == "P") {
            $("#divHoraSaida").slideDown();
            $("#lblHora").html("Hora de Entrada:");
            $(".opt_A").prop('disabled', true).hide();
            $(".opt_T").prop('disabled', true).hide();
            $(".opt_P").prop('disabled', false).show();
        }
        else if (tipo == "A") {
            $("#lblHora").html("Tempo a abonar:");
            $(".opt_A").prop('disabled', true).hide();
            $(".opt_P").prop('disabled', true).hide();
            $(".opt_T").prop('disabled', false).show();
        }
    });
    
    $("#data").datepicker({dateFormat:"dd/mm/yy", maxDate: 0});
    $("#data").mask("99/99/9999");
    $("#hora").mask("99:99");
    $("#horaSaida").mask("99:99");
    
    /**
     * **pedidoAjuste.js**
     * 
     * Evento que permitir ao servidor acompanhar o horário de outro vínculo caso
     * o mesmo esteja relacionado a mais de um vínculo.
     * 
     * @ignore
     * @event Evento onChange. Quando o valor do campo é alterado essa ação é executada
     */
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'ajuste/pedido/?v='+$(this).val();
        }
    });
});

/**
 * **pedidoAjuste.js**
 * 
 * Função utilizada para preparar e exibir o modal para criação de um novo pedido.
 * Aqui são incluidos os elementos do modal manualmente, dispensando o uso de um
 * arquivo externo contendo o código HTML do modal.
 * 
 * @returns {void} Prepara e exibe o modal para criação de um novo pedido
 */
function novoAjuste() {
    $("#divPedido input[type=text]").val('');
    $("#divPedido select").val('');
    $("#divPedido input[type=radio]").prop('checked', false);
    $("label[for=tipoA]").show();
    $("#tipoA").show();
    $("label[for=tipoP]").show();
    $("#tipoP").show();
    $("#mensagens").html('').hide();
    $("#divOutraJustificativa textarea").val('');
    $("#divOutraJustificativa").hide();
    $("#divPedido").dialog('open');
}

/**
 * **pedidoAjuste.js**
 * 
 * Função utilizada para exibir o campo para preenchimento da justificativa de 
 * ajuste caso a opção "Outra" da caixa de seleção seja selecionada.
 * 
 * @param {char} val Atributo value da caixa de seleção de justificativa
 * @returns {void} Abre ou fecha a caixa de inserção de texto da justificativa
 */
function verificaJustificativa(val) {
    $("#divOutraJustificativa").slideUp();
    if (val == "o") {
        $("#divOutraJustificativa").slideDown();
    }
}

/**
 * **pedidoAjuste.js**
 * 
 * Função utilizada para validar os campos do modal de pedido de ajuste, exibido
 * na tela Ajuste de Registros. Caso os dados necessários para realizar um pedido
 * de ajuste tenham sido informados, a função também solicita o ajuste.
 * 
 * @returns {void} Mostra na tela mensagem de erro ou sucesso na validação dos campos e da solicitação
 */
function enviaSolicitacao() {
    var msg = "";
    if ($("input[name=tipo]:checked").val() == undefined) {
        msg += "Selecione o tipo de ajuste. <br/>";
    } 
    if ($("#data").val() == "") {
        msg += "Selecione o dia para o ajuste. <br/>";
    }
    else {
        // Testa se é um dia válido
        var auxData = $("#data").val().split("/");
        auxData = auxData[2] + "/" + auxData[1] + "/" + auxData[0];
        if (!Date.parse(auxData)) {
            msg += "Escreva um dia válido. <br/>";
        }
        else {
            // Testa se o dia é maior que o atual
            var hoje = new Date();
            auxData = new Date(auxData);
            if (auxData > hoje) {
                msg += "O ajuste não pode ser solicitado para uma data futura. <br/>";
            }
        }
    }
    if ($("#hora").val() == "") {
        msg += "Selecione a hora "+($("input[name=tipo]:checked").val() == "P" ? 'de entrada ' : '')+"para o ajuste. <br/>";
    }
    else {
        // Testa se é um horário válido
        if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($("#hora").val())) {
            msg += "Escreva um horário válido. <br/>";
        }
    }
    if ($("input[name=tipo]:checked").val() == "P") {
        if ($("#horaSaida").val() == "") {
            msg += "Selecione a hora de saída para o ajuste. <br/>";
        }
        else if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($("#horaSaida").val())) {
            msg += "Escreva um horário de saída válido. <br/>";
        }
    }
    if (($("#justificativa").val() == "") || (($("#justificativa").val() == "o") && $("#outraJustificativa").val() == "")) {
        msg += "Justifique o seu pedido de ajuste. <br/>";
    }
    if (($("#justificativa").val() == "o") && ($("#outraJustificativa").val().length > 2048)) {
        msg += "Digite no máximo 2048 caracteres na justificativa (atualmente "+$("#outraJustificativa").val().length+"). <br/>"
    }
    if ($("#registroAnterior").val() == ($("input[name=tipo]:checked").val()+$("#data").val()+$("#hora").val())) {
        msg += "Você não alterou o dia e horário para solicitar o ajuste. <br/>";
    }
    var file = $("#anexos").prop('files')[0];
    if (file != undefined) {
        var size = file.size;
        var type = file.type;
        if (size > 1048576*5) {
            msg += "O arquivo anexo não pode ter mais do que 5 MiB. <br/>";
        }
        else if ((type != 'application/pdf') && (type != 'application/x-pdf') && 
                (type != 'image/jpeg') && (type != 'image/pjpeg') && (type != 'image/png') && (type != 'image/gif')) {
            msg += "O arquivo anexo precisa estar em formato PDF, JPG, PNG ou GIF. <br/>";
        }
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
            url: HOME+'/ajuste/enviarPedido',
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
                    $("#mensagens").html('').hide();
                    setTimeout(function(){window.location=HOME+"ajuste/pedido/"}, 2000);
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
 * **pedidoAjuste.js**
 * 
 * Função para exclusão de pedidos de ajuste. Caso todos os campos necessários
 * estejam preenchidos corretamente é feita uma requisição ao servidor para
 * exclusão do pedido.
 * 
 * @param {int} nr Número do pedido a ser excluido
 * @param {string} tipo Tipo do pedido. Na tela de ajustes tem o valor "ajuste"
 * @returns {void} Atualiza a tela em caso de sucesso ou exibe mensagem de erro
 */
function excluir(nr, tipo) {
    if (confirm("Tem certeza que deseja excluir esse pedido de ajuste?")) {
        $.ajax({
            type: 'POST',
            url: HOME + 'ajuste/excluirPedido',
            data: { nr: nr, tipo: tipo },
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
 * **pedidoAjuste.js**
 * 
 * Função para exibir a barra de pregresso de upload dos anexos utilizados no
 * pedido de ajustes.
 * 
 * @param {Event} e Evento a ser aplicado a animação
 * @returns {void} Mostra e aplica animação na barra de progresso
 */
function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}