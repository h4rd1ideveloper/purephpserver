<?php
include_once __DIR__ . '/Header.php';
require_once __DIR__ . '/components/Components.php';
Components::centredTitle('Verificação de Arrecadações Não processadas');
?>
<div class="row">
    <div class="col-8 mx-auto">
        <div class="d-flex flex-column justify-content-center"
             style=" border-radius:8px;  background-color: rgba(0,0,0,0.1);-webkit-backdrop-filter: blur(20px);backdrop-filter: blur(20px);">
            <form id="form" class="form-group form-group  w-50 mx-auto p-3 rounded" action='/send' method='POST'
                  enctype='multipart/form-data'>
                <div class="form-group">
                    <label class='btn btn-dark mx-auto text-center h2 w-100' for='fileToUpload'>Selecione a planilha
                        &#187;</label>
                    <input required class="form-control-file btn btn-secondary" type='file' name='fileToUpload'
                           id='fileToUpload'/>
                </div>
                <button id="submit" type="submit" class="btn btn-outline-secondary btn-lg btn-block">Enviar</button>
            </form>
        </div>
    </div>
</div>

<?php

include_once __DIR__ . '/Scripts.php';
?>
<script>
    $('document').ready(function () {
        $("#fileToUpload").on("change", function (e) {
            if (!this.value.includes('.xlsx')) {
                alert("Formato não aceito");
                $("#submit").hide();
                this.value = '';
                console.log($("#form").attr('action'), $("#form").attr('method'));
                $("#form").attr('action', '');
                $("#form").attr('method', '');
            } else {
                $("#submit").show();
                console.log($("#form").attr('action'), $("#form").attr('method'));
                $("#form").attr('action', '/send');
                $("#form").attr('method', 'POST');
            }
        });
        var typed = new Typed("#a1", {
            strings: ["..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a2", {
            strings: ["..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a3", {
            strings: ["..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
        var typed = new Typed("#a4", {
            strings: ["..."],
            typeSpeed: 500,
            backSpeed: 600,
            showCursor: false,
            loop: true
        });
    })
</script>

<?php
include_once __DIR__ . '/Footer.php';
?>
