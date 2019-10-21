(function () {
    $(document).ready(() => {
        $('#ComboBox2').html(
            '<option> Carregando...</option > '
        )
        var cpfcnpj = 'a.CPFCNPJ = ' + '43454594000113'; //buscando empregador atraves do averbador.
        var campo1 = "e.CPFCNPJEMPREGADOR";
        var campo2 = "e.DESCRICAO";
        $.ajax({
            method: "POST",
            url: "ajx/vincativos.php",
            data: { cpfcnpj: cpfcnpj, campo1: campo1, campo2: campo2 }
        }).done(function (data) { $('#ComboBox2').html(data); });
    })
})()