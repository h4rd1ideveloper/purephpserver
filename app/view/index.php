<?php
include_once  __DIR__ .'./Header.php';

?>
<div class="row my-5">
    <div class="col-12">
        <h1 class="h3 text-center text-black-50">
            Verificação de Arrecadações Não processadas
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <section style="max-height: 53vh !important; overflow-y: auto!important;">
            <table class="table table-striped table-hover" >
                <thead>
                <tr>
                    <th scope="col">Nº do cliente</th>
                    <th scope="col">Digito Verificador</th>
                    <th scope="col">Data Baixa</th>
                    <th scope="col">Correlativo</th>
                    <th scope="col">Tipo Doc</th>
                </tr>
                </thead>
                <tbody>

                    <tr>
                        <th scope="row">...</th>
                        <td id="a1">...</td>
                        <td id="a2">...</td>
                        <td id="a3">...</td>
                        <td id="a4">...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    <div class="col-6">
        <div class="d-flex flex-column justify-content-center"  style=" border-radius:8px;  background-color: rgba(0,0,0,0.1);-webkit-backdrop-filter: blur(20px);backdrop-filter: blur(20px);">
            <form class="form-group form-group  w-50 mx-auto p-3 rounded" action='?r=/send' method='POST' enctype='multipart/form-data'>
                <div class="form-group">
                    <label class='btn btn-dark mx-auto text-center h2 w-100' for='fileToUpload'>Selecione a planilha &#187;</label>
                    <input required class="form-control-file btn btn-secondary" type='file' name='fileToUpload' id='fileToUpload'/>
                </div>
                <button type="submit" class="btn btn-outline-secondary btn-lg btn-block">Enviar</button>
            </form>
        </div>
    </div>
</div>

<?php

include_once __DIR__ . './Scripts.php';
?>
<script>
    $('document').ready( function () {

        var typed = new Typed("#a1", {
            strings: [ "..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a2", {
            strings: [ "..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a3", {
            strings: [ "..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a4", {
            strings: [ "..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
    })
</script>

<?php
include_once  __DIR__ .'./Footer.php';
?>
