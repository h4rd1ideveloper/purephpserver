<?php

use App\view\components\Components;

Components::headerHTML(array('title' => 'Boletão'));
?>

<nav class="navbar navbar-light bg-light background-custom">
    <a class="navbar-brand text-white" href="#">Boletão</a>
</nav>

<main class="container-fluid">
    <form class="mt-5 container text-black-50">
        <div class="form-group row">
            <div class="col-4">
                <label for="condominio" class="col-form-label">Selecione o Condomínio</label>
                <select id="condominio" class="form-control form-control-sm" required>
                    <option selected value="-1">GERAL</option>
                </select>
            </div>
            <div class="col-4">
                <label for="nomecpf" class="col-form-label">Informe os Dados para Pesquisa do Cliente</label>
                <input id="nomecpf" type="text" class="form-control form-control-sm" aria-describedby="howToUseNomeCpf">
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
                <input class="form-control form-control-sm" dataformatas="" id="start" type="date" value="2011-08-19">
            </div>
            <div class="col-2">
                <label for="end" class="form-label2">Até:</label>
                <input class="form-control form-control-sm" dataformatas="" id="end" type="date" value="2011-08-19">
            </div>
            <div class="col-1 mt-auto">
                <button type="button" class="btn btn-sm btn-primary"><span class="mx-2">Filtrar</span></button>
            </div>
            <div class="col-2">
                <label for="vencimento" class="form-label2">Venc. Boleto</label>
                <input class="form-control form-control-sm" dataformatas="" id="vencimento" type="date" value="2011-08-19">
            </div>
            <div class="col-auto mt-auto ml-auto">
                <button id="relatorio" type="button" class=" btn btn-sm btn-primary">
                    <span class="mx-2">Relatorio</span>
                </button>
                <button id="attContrato" type="button" class="mx-2 btn btn-sm btn-primary">
                    <span class="mx-2">Atualizar Contrato</span>
                </button>
                <button id="grBoleto" type="button" class=" btn btn-sm btn-primary">
                    <span class="mx-2">Gerar Boletão</span>
                </button>
            </div>
        </div>
    </form>
    <section class="container my-5">
        <div class="row">
            <h1 class="h3 text-info align-text-bottom">Contratos encontrados</h1>
            <h4 class="h3 text-info ml-auto align-text-bottom mb-0">Total</h4>
            <input class=" text-muted form-control-plaintext w-fix ml-4 mr-0 number_ready_only borderless" type="text" readonly value="0,00">
            <span class="draw-row mb-3"></span>
        </div>
        <div class="row mt-5">

            <?php

            try {
                Components::tableHTML(
                    array(
                        'headers' => array('Condomínio', 'CPF/CNPJ', 'Nome', 'Contrato', 'Vencimento', 'Valor'),
                        'body' => array(
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '1', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '2', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '3', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '4', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '5', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '6', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '7', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '8', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '9', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '10', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '11', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '12', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '13', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '14', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '15', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '16', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '17', 'Vencimento', 'Valor'),
                            array('Condomínio', 'CPF/CNPJ', 'Nome', '18', 'Vencimento', 'Valor'),
                        )
                    ),
                    'listagem'
                );
            } catch (Exception $e) {
                return $e->getTraceAsString();
            }

            ?>
        </div>
    </section>
</main>
<?php
Components::footerHTML();
?>