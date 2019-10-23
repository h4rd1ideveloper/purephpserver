<?php

use App\view\components\Components;

$vars = isset($vars) && count($vars) ? $vars : null;
$flag = (isset($vars['table']) && count($vars['table']));
$error = isset($vars['error']);
Components::headerHTML(
    array(
        'title' => 'Boletão',
        'src' => "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8.18.6/dist/sweetalert2.min.js'></script><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@8.18.6/dist/sweetalert2.min.css'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js' integrity='sha256-S1J4GVHHDMiirir9qsXWc8ZWw74PHHafpsHp5PXtjTs=' crossorigin='anonymous'></script><script src='/portal/src/view/assets/js/moment-with-locales.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/rxjs/6.5.3/rxjs.umd.min.js' integrity='sha256-opvkRN8JcEjsFLLVkYM5d5n54MuZJBkljuRb5E4sQrk=' crossorigin='anonymous'></script>"
    )
);
?>


<nav class="navbar navbar-light bg-light background-custom">
    <a class="navbar-brand text-white" href="/portal/">Boletão</a>
</nav>

<main class="container-fluid">
    <form id="formulario" action="http://localhost/portal/boletos" method="post" enctype="multipart/form-data"
          class="mt-5 container text-black-50">
        <input type="hidden" name="averbador" id="averbador" value="43454594000113"/>

        <div class="form-group row">
            <div class="col-4">
                <label for="condominioValue" class="col-form-label">Selecione o Condomínio</label>
                <select id="condominioValue" name="condominioValue" class="form-control form-control-sm" required>
                    <option selected value="-1">GERAL</option>
                </select>
            </div>
            <div class="col-4">
                <label for="nomeOuCpf" class="col-form-label">Informe os Dados para Pesquisa do Cliente</label>
                <input id="nomeOuCpf" name="nomeOuCpf" type="text" class="form-control form-control-sm"
                       aria-describedby="howToUseNomeCpf"/>
                <small id="howToUseNomeCpf" class="form-text text-muted">(CPF ou o nome)</small>
            </div>
            <div class="col-4">
                <label for="situacao" class="col-form-label">Situação da Pessoa</label>
                <select id="situacao" class="form-control form-control-sm">
                    <option value="N" disabled selected>NORMAL</option>
                    <option value="D">DEMISSIONÁRIO</option>
                    <option value="F">FALECIDO</option>
                    <option value="I">INATIVO</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-2">
                <label for="start" class="form-label">De:</label>
                <input class="form-control form-control-sm" name="start" id="start" type="date" value="2019-01-01"/>
            </div>
            <div class="col-2">
                <label for="end" class="form-label2">Até:</label>
                <input class="form-control form-control-sm" name="end" id="end" type="date" value="2019-12-01">
            </div>
            <div class="col-2">
                <label for="vencimento" class="form-label2">Venc. Boleto</label>
                <input class="form-control form-control-sm" value="<?= date("Y-m-d"); ?>" id="vencimento" type="date"
                       value="2011-08-19">
            </div>
            <div class="col-auto mt-auto">
                <button id="filter" type="submit" class="mx-auto btn btn-sm btn-primary">
                    <span class="mx-2">Filtrar</span>
                </button>
                <?php if ($vars): ?>

                    <a id="relatorio"
                       target="_blank" href="http://localhost/portal" type="button"
                       class="mx-auto rounded btn btn-sm btn-primary" role="button">
                        <span class="mx-2">Relatorio</span>
                    </a>
                    <button id="attContrato" type="button" class="mx-auto btn btn-sm btn-primary">
                        <span class="mx-2">Atualizar Contrato</span>
                    </button>
                    <a id="grBoleto" href="http://localhost/portal" target="_blank" type="button"
                       class=" mx-auto btn btn-sm btn-primary disabled" role="button" aria-disabled="true">
                        <span class="mx-2">Gerar Boletão</span>
                    </a>
                <?php endif; ?>
                <input type="hidden" name="averbador" value="43454594000113"/>

            </div>
        </div>
    </form>


    <section class="container my-5">
        <div class="row">
            <h1 class="h3 text-info align-text-bottom">Contratos encontrados</h1>
            <h4 class="h3 text-info ml-auto align-text-bottom mb-0">Total</h4>
            <input class=" text-muted form-control-plaintext w-fix ml-4 mr-0 number_ready_only borderless"
                   type="text" readonly value="<?= isset($vars, $vars['total']) ? $vars['total'] : '0,00'; ?>"/>
            <span class="draw-row mb-3"></span>
        </div>
        <div class="row mt-1">
            <?php

            try {
                if ($vars) {
                    Components::tableHTML(
                        array(
                            'headers' => array('Condomínio', 'CPF/CNPJ', 'Nome', 'Contrato', 'Vencimento', 'Valor'),
                            'body' => isset($vars, $vars['table']) && count($vars['table']) ? $vars['table'] : array(
                                array('', '', '', '', '', '')
                            )
                        ),
                        'listagem'
                    );
                }

            } catch (Exception $e) {
                return $e->getTraceAsString();
            }

            ?>
        </div>
    </section>
</main>
<script>
    function atualizaUrlDeRelatorio() {
        let nomeOuCpf = '';
        if ($("#nomeOuCpf").val()) {
            nomeOuCpf = `&nomeOuCpf=${$("#nomeOuCpf").val()}`;
        } else {
            nomeOuCpf = '';
        }
        const url = `http://localhost/portal/api/relatorio?averbador=43454594000113&start=${$("#start").val()}&end=${$("#end").val()}&condominioValue=${$("#condominioValue").val()}`.concat(nomeOuCpf);
        $("#relatorio").prop(
            "href",
            url
        );
    }

    function startEventChange(e) {
        moment.locale('pt-BR');
        e.preventDefault();
        const {target} = e;
        const end = $("#end");
        if (moment(target.value).isAfter(end.val())) {
            $(target).removeClass('is-valid');
            $(target).addClass('is-invalid');
            end.removeClass('is-valid');
            end.addClass('is-invalid');
        } else {
            $(target).removeClass('is-invalid');
            $(target).addClass('is-valid');
            end.removeClass('is-invalid');
            end.addClass('is-valid');
        }
        atualizaUrlDeRelatorio();
    }

    function endEventChange(e) {
        moment.locale('pt-BR');
        e.preventDefault();
        const {target} = e;
        const start = $("#start");
        if (moment(target.value).isBefore(start.val())) {
            $(target).removeClass('is-valid');
            $(target).addClass('is-invalid');
            start.removeClass('is-valid');
            start.addClass('is-invalid');
        } else {
            $(target).removeClass('is-invalid');
            $(target).addClass('is-valid');
            start.removeClass('is-invalid');
            start.addClass('is-valid');
        }
        atualizaUrlDeRelatorio();
    }

    function vencimentoEventChange(e) {
        moment.locale('pt-BR');
        e.preventDefault();
        const {target} = e;
        const now = moment(new Date()).format("YYYY-MM-DD");
        const flag = moment(target.value).isAfter(now);
        if (!flag) {
            $(target).removeClass('is-valid');
            $(target).addClass('is-invalid');
        } else {
            $(target).removeClass('is-invalid');
            $(target).addClass('is-valid');
        }
    }

    function hendleSubmitForm(e) {
        e.preventDefault();
        if ($('input.is-invalid').length > 0) {
            Toast.fire({
                type: 'warning',
                title: 'Preencha corretamente os campos'
            });
            return false;
        }
        e.target.submit();
        return true;
    }

    function attContrato(e) {
        e.preventDefault();
        $("#attContrato").attr("disabled", "disabled");
        const data = {
            start: $("#start").val(),
            end: $("#end").val(),
            vencimento: $("#vencimento").val(),
            averbador: $("#averbador").val(),
            nomeOuCpf: $("#nomeOuCpf").val(),
            condominioValue: $("#condominioValue").val()
        };
        const gb = $(`#grBoleto`);
        gb.addClass('disabled');//
        gb.attr('aria-disabled');
        axios({
            url: 'portal/api/contratos/atualizar',
            method: 'PATCH',
            data
        }).then(({data}) => {
            console.table(data);
            if (
                data &&
                data['select_result'] &&
                data['select_result']['boletao'] &&
                data['select_result']['boletao'][0] &&
                data['select_result']['boletao'][0]['last_id'] &&
                data['select_result']['boletao'][0]['last_id'] !== undefined &&
                data['select_result']['boletao'][0]['last_id'] !== "undefined" &&
                data['select_result']['boletao'][0]['last_id'] !== null &&
                data['select_result']['boletao'][0]['last_id']
            ) {
                gb.prop('href', `//emprestacap.com.br/teste/webagente+/boleto_bradesco.php?cod=${data['select_result']['boletao'][0]['last_id']}`);
                gb.removeClass('disabled');//
                gb.removeAttr('aria-disabled');
                Toast.fire({
                    type: 'success',
                    title: 'Os vencimentos foram Atualizados'
                });
                $("#attContrato").removeAttr("disabled")
            }
        }).catch(error => {
            console.error(error);
            Toast.fire({
                type: 'error',
                title: 'Falha ao atualizar vencimentos'
            });
            $("#attContrato").removeAttr("disabled");
            gb.removeClass('disabled');//
            gb.removeAttr('aria-disabled');
        });
        return false;
    }

    (
        ($) => {
            return $('document').ready(function () {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $("#formulario").on('submit', hendleSubmitForm);
                condominio = $('#condominioValue');
                $("#attContrato").on('click', attContrato);
                moment.locale('pt-BR');
                condominio.html('<option> Carregando...</option>');
                const cpfcnpj = 'a.CPFCNPJ = ' + $("#averbador").val(); //document.getElementById('ComboBox1').value;
                const campo1 = "e.CPFCNPJEMPREGADOR";
                const campo2 = "e.DESCRICAO";
                $.ajax({
                    method: "POST",
                    url: 'http://localhost/portal/api/comdominios',
                    data: {cpfcnpj, campo1, campo2}
                }).done(function (data) {
                    console.log(data);
                    condominio.html(data);
                    atualizaUrlDeRelatorio();
                });
                $("#start").on('change', startEventChange);
                $("#end").on('change', endEventChange);
                $("#vencimento").on('change', vencimentoEventChange);
                $('#condominioValue,#nomeOuCpf').on('change', (e) => {
                        e.preventDefault();
                        atualizaUrlDeRelatorio();
                    }
                );
            });
        }
    )
    ($ = window.jQuery, axios = window.axios, Swal = window.Swal)
</script>
<?php
if ($error):
    ?>
    <script>
        Toast.fire({
            type: 'error',
            title: 'Algo deu errado, contate o ADM do sistema'
        });
    </script>
<?php endif; ?>
<?php
Components::footerHTML();
?>
