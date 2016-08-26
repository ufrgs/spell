$(document).ready(function () {
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

function alteraRestricao(nr, nome, ipv4, ipv6) {
    $("#CodRestricao").val(nr);
    $("#nomeRestricao").html(nome);
    $("#ipv4").val(ipv4);
    $("#ipv6").val(ipv6);
    $("#modal").dialog("open");
}

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