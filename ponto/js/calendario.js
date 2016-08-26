$(document).ready(function () {
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'calendario/index/?v='+$(this).val();
        }
    });
    $("#ano").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'calendario/index/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });
    $("#mes").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'calendario/index/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
});