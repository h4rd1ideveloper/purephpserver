<?php

namespace App\view\components;

use App\assets\lib\Helpers;
use InvalidArgumentException;

class Components
{
    public static function headerHTML(array $config)
    {
        $title = Helpers::orEmpty(
            $config['title'],
            false,
            'Boletão'
        );
        //$basePath = $_SERVER['HTTP_HOST'];
        $html =
            /**@lang HTML */
            "<!doctype html>
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport'
                      content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
                <meta http-equiv='X-UA-Compatible' content='ie=edge'>
                <title>$title</title>
                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'
                      integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
                <script src='/portal/src/view/assets/js/jquery-3.4.1.min.js' ></script>
                <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'
                        integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM'
                        crossorigin='anonymous'></script>
                <link rel='stylesheet' href='/portal/src/view/assets/css/index.css'/>
                <link href='/portal/src/view/assets/fonts/fontawesome/css/all.min.css' rel='stylesheet'>
                <script src='/portal/src/view/assets/fonts/fontawesome/js/all.min.js'></script>

                <link href='/portal/src/view/assets/css/jquery.dataTables.min.css' rel='stylesheet'>
                <script src='/portal/src/view/assets/js/jquery.dataTables.min.js'></script>
            </head>
            <body>";
        echo $html;
    }
    public static function footerHTML(array $config = array())
    {

        $html =
            /**@lang HTML */
            '
        <script>
        $(document).ready(function() {
            $("#listagem").DataTable();
        });
        </script>
        </body>
        </html>
        ';
        echo $html;
    }
    public static function tableHTML(array $data, $id)
    {
        if (count($data) === 0) {
            throw new InvalidArgumentException("O array não pode ser Vazio");
        }
        if (!isset($data['headers']) || !is_array($data['headers'])) {
            throw new InvalidArgumentException("Atributo headers é obrigatorio e deve ser um array de valores escalar");
        }
        if (!isset($data['body']) || !is_array($data['body'])) {
            throw new InvalidArgumentException("Atributo headers é obrigatorio e deve ser um array de valores escalar");
        }
        $html = '';
        $html .= Helpers::Reducer($data['headers'], function ($initial, $currentValue, $index) {
            return $initial . "<th scope='col'>$currentValue</th>";
        }, "<table id='$id' class='table table-hover'><thead><tr>") . '</tr></thead>';
        $html .= Helpers::Reducer($data['body'], function ($initial, $currentValue, $index) {
            if (!is_array($currentValue)) {
                throw new InvalidArgumentException("Atributo body é obrigatorio e deve ser um array de array valores escalar");
            }
            return $initial . Helpers::Reducer($currentValue, function ($init, $v, $key) {
                return $init . "<th class='text-black-50' scope='row'>$v</th>";
            }, '<tr>') . '</tr>';
        }, '<tbody>') . '</tbody></table>';
        echo $html;
    }
    public static function centredTitle($text, $size = 'h3')
    {
        echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="%s text-center text-black-50">%s </h1>
                </div>
            </div>
        ', $size, $text);
    }
    public static function leftTitle($text, $text_class = '')
    {
        if ($text_class === '') {
            echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="h3 text-black-50 ">%s </h1>
                </div>
            </div>
        ', $text);
        } else {
            echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="%s">%s </h1>
                </div>
            </div>
        ', $text_class, $text);
        }
    }
}
