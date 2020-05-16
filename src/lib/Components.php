<?php

namespace App\lib;

use Exception;
use InvalidArgumentException;

/**
 * Class Components
 * @package App\view\components
 */
class Components
{

    /**
     * @var string HTML Content
     */
    private static string $HTML_CONTENT;

    /**
     * @param string $templateFileName
     * @param array $context
     * @return string
     * @throws Exception
     */
    public static function Sender(string $templateFileName, ?array $context = []): string
    {
        return Helpers::viewFileAsString($templateFileName, true, $context ?? []);
    }

    /**
     * @param array $config
     * @return string
     */
    public static function headerHTML(array $config = []): string
    {
        self::$HTML_CONTENT = '';
        $baseUrl = substr(Helpers::baseURL(), -1) !== '/' ? Helpers::baseURL() : substr(Helpers::baseURL(), 0, -1);
        $title = $config['title'] ?? 'Home';
        $keywords = $config['keywords'] ?? '';
        $description = $config['description'] ?? '';
        $more = $config['raw'] ?? '';
        $bodyClass = $config['bodyClass'] ?? '';
        self::$HTML_CONTENT .=
            /**@lang text */
            "
            <!doctype html>
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'/>
                <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'/>
                <meta http-equiv='X-UA-Compatible' content='ie=edge'/>
                <meta content='$keywords' name='keywords'>
                <meta content='$description' name='description'>
                <link rel='stylesheet' href='$baseUrl/src/pages/css/bootstrap.min.css' >
        ";
        if (isset($config['stylesheet']) && $config['stylesheet']) {
            self::$HTML_CONTENT .= Helpers::Reducer(
                $config['stylesheet'],
                fn($initialValue, $value, $key) => sprintf("%s<link rel='stylesheet' href='%s' >%s", $initialValue, $value, PHP_EOL),
                ''
            );
        }
        self::$HTML_CONTENT .=
            /**@lang text */
            "

        $more
        <title>$title</title>
    </head>
    <body class='$bodyClass'>

";
        return self::$HTML_CONTENT;
    }

    /**
     * @TODO param array $config
     * @param array $more
     * @return string
     */
    public static function scripts(array $more = []): string
    {
        return count($more) && Helpers::isArrayOf('string', $more) ? Helpers::Reducer(
            $more,
            fn($initialValue, $value, $key) => sprintf("%s<script type='text/javascript' src='%s'></script> %s", $initialValue, $value, PHP_EOL),
            ''
        ) : "
            <script src='//code.jquery.com/jquery-3.4.1.slim.min.js' integrity='sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n' crossorigin='anonymous'></script>
            <script src='//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js' integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo' crossorigin='anonymous'></script>
            <script src='//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js' integrity='sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6' crossorigin='anonymous'></script>
            <script type='text/javascript' src='//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js'></script>
      ";
    }

    public static function closeView()
    {
        return "</body></html>";
    }

    /**
     * @param string $name
     * @param bool $required
     * @param string|null $label
     * @param string|null $labelHelper
     * @param string|null $type
     * @param string|null $placeholder
     * @param string|null $class
     * @param string|null $wrapClass
     * @param int|null $min
     * @param int|null $max
     * @return Components
     */
    public static function input(string $name, bool $required, ?string $label = '', ?string $labelHelper = '', ?string $type = 'text', ?string $placeholder = '', ?string $class = '', ?string $wrapClass = '', ?int $min = 4, ?int $max = 20)
    {
        $key = uniqid();
        ?>
        <div class="form-group <?= $wrapClass; ?>">
            <label for="<?= $name; ?>"><?= $label; ?>
                <input <?php if ($required) : ?>required<?php endif; ?> maxlength="<?= $max; ?>"
                       minlength="<?= $min; ?>" type="<?= $type; ?>" class="form-control <?= $class; ?>"
                       name="<?= $name; ?>" aria-describedby="<?= $name; ?>HelpId_<?= $key; ?>"
                       placeholder="<?= $placeholder; ?>"/>
            </label>
            <small id="<?= $name; ?>HelpId_<?= $key; ?>" class="form-text text-secondary"><?= $labelHelper; ?></small>
        </div>
        <?php
        return new static();
    }

    /**
     * @param array $data
     * @param $id
     */
    public static function tableHTML(array $data, $id)
    {
        if (count($data) === 0) {
            throw new InvalidArgumentException("O array não pode ser Vazio");
        }
        if (!isset($data['headers']) || !is_array($data['headers'])) {
            throw new InvalidArgumentException("Atributo headers é obrigatorio e deve ser um array de valores escalar");
        }
        if (!isset($data['body']) || !is_array($data['body']) || !is_array($data['body'][0])) {
            throw new InvalidArgumentException("Atributo body é obrigatorio e deve ser um array de array");
        }
        $html = '';
        $html .= sprintf(
        /**@lang text */
            "%s</tr></thead>",
            Helpers::Reducer(
                $data['headers'],
                fn($initial, $currentValue) => sprintf(
                /**@lang text */
                    "%s<th scope='col'>%s</th>",
                    $initial,
                    $currentValue
                ),
                sprintf(
                /**@lang text */
                    "<table id='%s' class='table table-hover'><thead><tr>",
                    $id
                )
            )
        );
        $html .= sprintf(
        /**@lang text */
            "%s</tbody></table>",
            Helpers::Reducer(
                $data['body'],
                fn($initial, $currentValue) => sprintf(
                /**@lang text */
                    "%s%s</tr>",
                    $initial,
                    Helpers::Reducer(
                        $currentValue,
                        fn($init, $v) => sprintf(
                        /**@lang text */
                            "%s<th class='text-black-50' scope='row'>%s</th>",
                            $init,
                            $v
                        ),
                        /**@lang text */
                        '<tr>'
                    )
                ),
                /**@lang text */
                '<tbody>'
            )
        );
        echo $html;
    }
}
