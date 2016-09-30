/**
 * ** registro.js **
 * 
 * Função utilizada para atualizar o horário do relógio exibido na tela.
 * 
 * @returns {void} Mostra na tela o valor atualizado do horário
 */
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

/**
 * ** registro.js **
 * 
 * Proporção de tamanho dos textos da página
 * 
 * @type int
 */
var size = 1;

/**
 * ** registro.js **
 * 
 * Variável para controle de registro no ponto. Tem valor TRUE caso já tenha
 * sido feito ponto e FALSE caso contrário.
 * 
 * É utilizada na função <code>fazRegistroPonto(tipo)</code>
 * 
 * @type Boolean
 */
var controleRegistroPonto = true;


$(document).ready(function () {
    // Atualiza o relógio a cada minuto
    setTimeout(atualizaRelogio, $("#segundosAteAtualizar").val() * 1000);

    if ($("#nrVinculo") != undefined) {
        // Faz logout no sistema após 2 minutos de inatividade
        setTimeout(function () {
            window.location = HOME + 'registro/sair'
        }, 120000);
    }

    $('#progressoDiario').circleProgress({
        value: $('#progressoDiario').attr('value'),
        size: 120,
        thickness: 15,
        fill: {color: "#ada"}
    });
    $('#progressoSemanal').circleProgress({
        value: $('#progressoSemanal').attr('value'),
        size: 120,
        thickness: 15,
        fill: {color: "#6c6"}
    });
    $('#progressoMensal').circleProgress({
        value: $('#progressoMensal').attr('value'),
        size: 120,
        thickness: 15,
        fill: {color: "#3a3"}
    });

    $("#selVinculo").change(function () {
        if ($(this).val() != '') {
            window.location = HOME + 'registro/index/?v=' + $(this).val();
        }
    });
    if ($("#tipoUltimoRegistro").val() == 'S') {
        $("#btEntrada").focus();
    } else if ($("#tipoUltimoRegistro").val() == 'E') {
        $("#btSaida").focus();
    } else {
        if ($("#usuario").val() == "") {
            $("#usuario").focus();
        } else {
            $("#senha").focus();
        }
        $("#usuario").mask("9?9999999", {placeholder: ""});
    }
});

/**
 * ** registro.js **
 * 
 * Função utilizada para validação do login do servidor.
 * 
 * @returns {Boolean} TRUE em caso de senha não infomada e FALSE caso contrário
 */
function verificaSenhaEmBranco() {
    if ($("#senha").val() == '') {
        $("#senha").focus();
        return false;
    }
    return true;
}

/**
 * ** registro.js **
 * 
 * Função utilizada para implementar o recurso de acessibilidade que aumenta o 
 * tamanho da fonte dos textos contidos na página.
 * 
 * @returns {void} Altera o estilo da página
 */
function aumentarTexto() {
    if (size < 1.5)
        size += 0.2;
    if (size > 1.5)
        $("#acessibilidade a:last").hide();
    $("#acessibilidade a:first").show();
    window.parent.document.body.style.zoom = size;
}

/**
 * ** registro.js **
 * 
 * Função utilizada para implementar o recurso de acessibilidade que diminui o 
 * tamanho da fonte dos textos contidos na página.
 * 
 * @returns {void} Altera o estilo da página
 */
function diminuirTexto() {
    if (size > 1)
        size -= 0.2;
    if (size <= 1)
        $("#acessibilidade a:first").hide();
    $("#acessibilidade a:last").show();
    window.parent.document.body.style.zoom = size;
}

/**
 * ** registro.js **
 * 
 * Função utilizada como disparador da função <code>registraPonto(tipo)</code> 
 * indicando que uma solicitação de registro de entrada.
 * 
 * @returns {void} Mostra na tela o retorno da função registraPonto(tipo)
 */
function registraEntrada() {
    $("#btEntrada").blur();
    registraPonto('E');
}

/**
 * ** registro.js **
 * 
 * Função utilizada como disparador da função <code>registraPonto(tipo)</code> 
 * indicando que uma solicitação de registro de saída.
 * 
 * @returns {void} Mostra na tela o retorno da função registraPonto(tipo)
 */
function registraSaida() {
    $("#btSaida").blur();
    registraPonto('S');
}

/**
 * ** registro.js **
 * 
 * Função utilizada para realizar o registro de ponto do usuário.
 * 
 * Em caso de falha uma mensagem de erro é mostrada na tela e a função é finalizada.
 * 
 * Antes de realizar o registo de ponto, a função faz algumas verificações em 
 * busca de alterar o servidor de possíveis problemas no registro, como realizar
 * uma entrada com um intervalo menor do que 1 hora e realizar um pedido de entrada
 * sem ter feito um pedido de saída anteriormente, por exemplo.
 * 
 * Caso seja detectado um conflito um modal será exibido podendo conter botões 
 * de confirmação da operação (sim ou não) ou apenas um alerta temporário.
 * 
 * @param {char} tipo Tipo de de registro a ser feito (E para entrada e S para saída)
 * @returns {void} Mostra na tela uma mensagem de sucesso ou falha na operação
 */
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
                        {titulo: 'Sim', acao: function () {
                                fazRegistroPonto(tipo)
                            }},
                        {titulo: 'Não', acao: function () {
                                fechaModal()
                            }},
                    ]);
                } else if (tipo == 'E') {
                    if (ultimoRegistro.tipo == 'S') {
                        // consistir horario de intervalo
                        if (tempoDesdeUltimoRegistro < 60) {
                            // intervalo menor que 1 hora
                            ok = false;
                            abreModal("Atenção!", "Você está voltando de um intervalo menor do que 1 hora. Confirma entrada?", [
                                {titulo: 'Sim', acao: function () {
                                        fazRegistroPonto(tipo)
                                    }},
                                {titulo: 'Não', acao: function () {
                                        fechaModal()
                                    }},
                            ]);
                        } else if (tempoDesdeUltimoRegistro > 180) {
                            // intervalo maior que 3 horas
                            abreModal("Atenção!", "Você está voltando de um intervalo maior do que 3 horas.");
                        }
                    } else {
                        // entrada sem saída
                        ok = false;
                        abreModal("Atenção!", "Você está fazendo uma entrada sem ter feito uma saída. Confirma entrada?", [
                            {titulo: 'Sim', acao: function () {
                                    fazRegistroPonto(tipo)
                                }},
                            {titulo: 'Não', acao: function () {
                                    fechaModal()
                                }},
                        ]);
                    }
                } else {
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
                    } else {
                        // saida sem entrada
                        ok = false;
                        abreModal("Atenção!", "Você esté fazendo uma saída sem ter feito uma entrada. Confirma saída?", [
                            {titulo: 'Sim', acao: function () {
                                    fazRegistroPonto(tipo)
                                }},
                            {titulo: 'Não', acao: function () {
                                    fechaModal()
                                }},
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

/**
 * ** registro.js **
 * 
 * Função utilizada para fazer o registro no ponto caso ainda não tenha sido feito.
 * 
 * @param {char} tipo Tipo de registro a se feito (E para entreada, S para saída)
 * @returns {void} Exibe um modal conte uma mensagem de sucesso ou falha na operação
 */
function fazRegistroPonto(tipo) {
    // Verifica se a variável de controle está livre
    if (controleRegistroPonto == true) {
        controleRegistroPonto = false;

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
                setTimeout(function () {
                    window.location = HOME + 'registro/sair'
                }, 2000);
            },
            error: function (retorno) {
                // Em caso de falha na operação a variável de controle é liberada
                controleRegistroPonto = true;
                abreModal("Ocorreu um erro", retorno);
            }
        });
    }
}

/**
 * ** registro.js **
 * 
 * Função auxiliar para abertura de um modal na tela do usuário. É utiliada para
 * mostrar mensagens de erro e sucesso.
 * 
 * Essa função aceita que botões sejam anexados ao modal aberto. Os botões são
 * representados utilizando JSON abaixo:
 * 
 * ```
 * [
 *    {
 *       titulo: "", //Texto a ser exibido dentro do botão
 *       acao: () => {} //função a ser utilizada como evento de clique
 *    }
 * ]
 * ```
 * 
 * @param {string} titulo Um texto a ser mostrado em destaque no modal
 * @param {string} conteudo O conteúdo a ser mostrado contro do modal
 * @param {object} botoes Botões a serem anexados ao modal
 * @returns {void} Exibe o modal na tela
 */
function abreModal(titulo, conteudo, botoes) {
    fechaModal();
    $("#janelaModal .tituloCard").html(titulo);
    $("#janelaModal .conteudo").html(conteudo);
    if ((botoes != undefined) && (botoes != "")) {
        var botao = null;
        console.log(botoes);
        for (var i = 0; i < botoes.length; i++) {
            botao = $('<button>' + botoes[i].titulo + '</button>').click(botoes[i].acao);
            $("#janelaModal .botoes").append(botao);
        }
    }
    $("#fundoModal").show();
    $("#janelaModal").slideDown();
    $("#janelaModal .botoes button:first").focus();
}

/**
 * ** registro.js **
 * 
 * Função auxiliar para fechamento do modal aberto pela função
 * <code>abreModal(titulo, conteudo, botoes)</code>.
 * 
 * @returns {void} Esconde o modal criado utilizando o método hide() do jQuery
 */
function fechaModal() {
    $("#janelaModal .titulo").html("");
    $("#janelaModal .conteudo").html("");
    $("#janelaModal .botoes").html("");
    $("#janelaModal").slideUp();
    $("#fundoModal").hide();
}