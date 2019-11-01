<?php

use App\view\components\Components;

$vars = isset($vars) && count($vars) ? $vars : false;

Components
    ::headerHTML(array('title' => 'Developer Web Page'))
    ::navbarHTML(
        [
            'page_href' => 'home',
            'page_title' => 'Inicio',
            'nav_items' => [
                'Inicio' => 'home'
            ]
        ],
        'dark');
?>

<script>

    (
        function ($, axios, Swal) {
            $('document').ready(function () {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }
    )
    ($ = window.jQuery, axios = window.axios, Swal = window.Swal)
</script>
<?php
if (isset($vars['error']) && !!$vars['error']):?>
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
