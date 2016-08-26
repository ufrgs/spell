var atualizaRelogio = function () {
    $.ajax({
        url: HOME + 'registro/atualizaRelogio',
        success: function (retorno) {
            $("#relogio").html(retorno);
            setTimeout(atualizaRelogio, 60000);
        },
        error: function (retorno) {
            $("#relogio").html($("#relogio").html() + " (não atualizado)");
        }
    });
}

var size = 1;

// variavel que controla o registro de ponto e forca a execucao do trecho de codigo uma unica vez.
// utilizada na funcao fazRegistroPonto()
var controleRegistroPonto=true;


$(document).ready(function () {
    // atualiza o relogio a cada minuto
    setTimeout(atualizaRelogio, $("#segundosAteAtualizar").val()*1000);
    
    if ($("#nrVinculo") != undefined) {
        // sai em 2 minutos de inatividade
        setTimeout(function() { window.location = HOME + 'registro/sair'}, 120000);
    }
    
    $('#progressoDiario').circleProgress({
        value: $('#progressoDiario').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#ada" }
    });
    $('#progressoSemanal').circleProgress({
        value: $('#progressoSemanal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#6c6" }
    });
    $('#progressoMensal').circleProgress({
        value: $('#progressoMensal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#3a3" }
    });
    
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'registro/index/?v='+$(this).val();
        }
    });
    if ($("#tipoUltimoRegistro").val() == 'S') {
        $("#btEntrada").focus();
    }
    else if ($("#tipoUltimoRegistro").val() == 'E') {
        $("#btSaida").focus();
    }
    else {
        if ($("#usuario").val() == "") {
            $("#usuario").focus();
        }
        else {
            $("#senha").focus();
        }
        $("#usuario").mask("9?9999999", {placeholder:""});
    }
});

function verificaSenhaEmBranco() {
    if ($("#senha").val() == '') {
        $("#senha").focus();
        return false;
    }
    return true;
}

function aumentarTexto() {
    if (size < 1.5)
        size += 0.2;
    if (size > 1.5) 
        $("#acessibilidade a:last").hide();
    $("#acessibilidade a:first").show();
    window.parent.document.body.style.zoom = size;
}

function diminuirTexto() {
    if (size > 1)
        size -= 0.2;
    if (size <= 1) 
        $("#acessibilidade a:first").hide();
    $("#acessibilidade a:last").show();
    window.parent.document.body.style.zoom = size;
}

function registraEntrada() {
    $("#btEntrada").blur();
    registraPonto('E');
}

function registraSaida() {
    $("#btSaida").blur();
    registraPonto('S');
}

function registraPonto(tipo) {
    $.ajax({
        url: HOME + 'registro/getUltimoRegistroEJornada',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'nrVinculo': $("#nrVinculo").val(),
        },
        success: function (retorno) {
            var ultimoRegistro = retorno.ultimoRegistro;
            var tempoDesdeUltimoRegistro = retorno.agora - ultimoRegistro.hora;
            var ok = true;
            
            if (ultimoRegistro.tipo != undefined) {
                if (tempoDesdeUltimoRegistro < 10) {
                    // novo registro antes de 10 minutos apos ultimo registro
                    ok = false;
                    abreModal("Atenção!", "Você está fazendo um novo registro em menos de 10 minutos após seu último registro. Confirma?", [
                        {titulo: 'Sim', acao: function() {fazRegistroPonto(tipo)}},
                        {titulo: 'Não', acao: function() {fechaModal()}},
                    ]);
                }
                else if (tipo == 'E') {
                    if (ultimoRegistro.tipo == 'S') {
                        // consistir horario de intervalo
                        if (tempoDesdeUltimoRegistro < 60) {
                            // intervalo menor que 1 hora
                            ok = false;
                            abreModal("Atenção!", "Você está voltando de um intervalo menor do que 1 hora. Confirma entrada?", [
                                {titulo: 'Sim', acao: function() {fazRegistroPonto(tipo)}},
                                {titulo: 'Não', acao: function() {fechaModal()}},
                            ]);
                        }
                        else if (tempoDesdeUltimoRegistro > 180) {
                            // intervalo maior que 3 horas
                            abreModal("Atenção!", "Você está voltando de um intervalo maior do que 3 horas.");
                        }
                    }
                    else {
                        // entrada sem saída
                        ok = false;
                        abreModal("Atenção!", "Você está fazendo uma entrada sem ter feito uma saída. Confirma entrada?", [
                            {titulo: 'Sim', acao: function() {fazRegistroPonto(tipo)}},
                            {titulo: 'Não', acao: function() {fechaModal()}},
                        ]);
                    }
                }
                else {
                    if (ultimoRegistro.tipo == 'E') {
                        // ver jornada do turno
                        if (tempoDesdeUltimoRegistro > 360) {
                            abreModal("Atenção!", "Você está saíndo de um turno maior que 6 horas.");
                        }
                        // ver jornada diaria
                        else if (retorno.jornadaDiaria > 600) {
                            // jornada maior que 10 horas
                            abreModal("Atenção!", "Você fez uma jornada maior que 10 horas hoje.");
                        }
                    }
                    else {
                        // saida sem entrada
                        ok = false;
                        abreModal("Atenção!", "Você esté fazendo uma saída sem ter feito uma entrada. Confirma saída?", [
                            {titulo: 'Sim', acao: function() {fazRegistroPonto(tipo)}},
                            {titulo: 'Não', acao: function() {fechaModal()}},
                        ]);
                    }
                }
            }
            if (ok) {
                fazRegistroPonto(tipo);
            }
        },
        error: function (retorno) {
            abreModal("Atenção!", "Não foi possível fazer consistências...");
        }
    });
}

function fazRegistroPonto(tipo) {
    
	//alert(controleRegistroPonto);
	// verifica se a variavel de controle esta livre
	if(controleRegistroPonto==true)
	{
		controleRegistroPonto=false;
		$.ajax({
			url: HOME + 'registro/registraPonto',
			type: 'POST',
			dataType: 'JSON',
			data: {
				'tipo': tipo,
				'nrVinculo': $("#nrVinculo").val(),
			},
			success: function (retorno) {
				abreModal("", retorno.msg);
				setTimeout(function(){window.location=HOME+'registro/sair'}, 2000);
			},
			error: function (retorno) {
				// caso da erro, libera a variavel de controle
				controleRegistroPonto=true;
				abreModal("Ocorreu um erro", retorno);
			}
		});
	}
}

function abreModal(titulo, conteudo, botoes) {
    fechaModal();
    $("#janelaModal .tituloCard").html(titulo);
    $("#janelaModal .conteudo").html(conteudo);
    if ((botoes != undefined) && (botoes != "")) {
        var botao = null; console.log(botoes);
        for (var i = 0; i < botoes.length; i++) {
            botao = $('<button>'+botoes[i].titulo+'</button>').click(botoes[i].acao);
            //console.log(botao);
            $("#janelaModal .botoes").append(botao);
        }
    }
    $("#fundoModal").show();
    $("#janelaModal").slideDown();
    $("#janelaModal .botoes button:first").focus();
}

function fechaModal() {
    $("#janelaModal .titulo").html("");
    $("#janelaModal .conteudo").html("");
    $("#janelaModal .botoes").html("");
    $("#janelaModal").slideUp();
    $("#fundoModal").hide();
}