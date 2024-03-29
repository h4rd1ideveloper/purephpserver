<?php

namespace App\infra\service\render;

use App\infra\lib\Helpers;
use Exception;
use InvalidArgumentException;

/**
 * Class Components
 * @package App\view\components
 */
class Components extends Helpers
{

    /**
     * @var string HTML Content
     */
    private static string $HTML_CONTENT;

    private static string $pathToViewers;

    protected function __construct(?string $VIEW_PATH)
    {
        self::setPathToViewers($VIEW_PATH ?? environments('path_viewers'));
    }

    /**
     * @param string $html
     * @return string
     */
    protected static function pipe(string $html): string
    {
        return $html;
    }

    /**
     * @param array $config
     * @return string
     */
    protected static function headerHTML(array $config = []): string
    {
        self::$HTML_CONTENT = '';
        $baseUrl = substr(Helpers::baseURL(), -1) !== '/' ? Helpers::baseURL() : substr(Helpers::baseURL(), 0, -1);
        $title = $config['title'] ?? 'Home';
        $keywords = $config['keywords'] ?? '';
        $description = $config['description'] ?? '';
        $more = $config['raw'] ?? '';
        $bodyClass = $config['bodyClass'] ?? '';
        self::$HTML_CONTENT .=
            <<<HTML
        <!doctype html>
        <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'/>
                <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'/>
                <meta http-equiv='X-UA-Compatible' content='ie=edge'/>
                <meta content='$keywords' name='keywords'>
                <meta content='$description' name='description'>
                <link rel='stylesheet' href='$baseUrl/src/pages/css/bootstrap.min.css' >

        HTML;
        if (isset($config['stylesheet']) && $config['stylesheet'] && Helpers::isArrayOf('string', $config['stylesheet'])) {
            self::$HTML_CONTENT .= self::linkFrom($config['stylesheet']);
        }
        if (isset($config['scripts']) && $config['scripts'] && Helpers::isArrayOf('string', $config['scripts'])) {
            self::$HTML_CONTENT .= self::scriptsFrom($config['scripts']);
        }
        self::$HTML_CONTENT .= <<<HTML
                $more
                <title>$title</title>
            </head>
            <body class='$bodyClass'>

        HTML;
        return self::$HTML_CONTENT;
    }

    /**
     * @param array $links
     * @return string
     */
    protected static function linkFrom(array $links): string
    {
        return Helpers::Reducer(
            $links,
            fn($initialValue, $value, $key) => sprintf("%s<link rel='stylesheet' href='%s' >%s", $initialValue, $value, PHP_EOL),
            ''
        );
    }

    /**
     * @param array $scripts
     * @return string
     */
    protected static function scriptsFrom(array $scripts): string
    {
        return Helpers::Reducer(
            $scripts,
            fn($initialValue, $value, $key) => /**@lang text */ "$initialValue <script type='text/javascript' src='$value'></script>" . PHP_EOL, PHP_EOL
        );
    }

    /**
     * @TODO param array $config
     * @param array $more
     * @param string $raw
     * @return string
     */
    protected static function scripts(array $more = [], string $raw = ''): string
    {
        return <<<HTML
        
                <script src='//code.jquery.com/jquery-3.4.1.slim.min.js' integrity='sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n' crossorigin='anonymous'></script>
                <script src='//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js' integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo' crossorigin='anonymous'></script>
                <script src='//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js' integrity='sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6' crossorigin='anonymous'></script>
                <script type='text/javascript' src='//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js'></script>

        HTML. self::scriptsFrom($more) . $raw;
    }

    /**
     * @return string
     */
    protected static function closeView(): string
    {
        return <<<HTML
    
    </body>
</html>
HTML;
    }

    protected static function input(string $name, bool $required = false, ?string $label = '', ?string $labelHelper = '', ?string $type = 'text', ?string $placeholder = '', ?string $class = '', ?string $wrapClass = '', ?int $min = 4, ?int $max = 20): string
    {
        $key = uniqid('', true);
        $_required = $required ? "required" : '';
        $helper_id = $name . "HelpId_$key";
        $attrs = "$_required maxlength='$max' minlength='$min' type='$type' class='form-control $class' name='$name' aria-describedby='$helper_id' placeholder='$placeholder'";
        return <<<HTML
                <div class='form-group $wrapClass'>
                    <label for='$name'>$label
                        <input  $attrs />
                    </label>
                    <small id='$helper_id' class='form-text text-secondary'>$labelHelper</small>
                </div>
            HTML;
    }

    /**
     * @param array $data
     * @param $id
     * @return string
     */
    protected static function tableHTML(array $data, $id): string
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
        $html = sprintf(
            "%s</tr></thead>",
            Helpers::Reducer(
                $data['headers'],
                fn($initial, $currentValue) => sprintf("%s<th scope='col'>%s</th>", $initial, $currentValue),
                sprintf("<table id='%s' class='table table-hover'><thead><tr>", $id)
            )
        );
        $html .= sprintf(
            "%s</tbody></table>",
            Helpers::Reducer(
                $data['body'],
                fn($initial, $currentValue) => sprintf(
                    "%s%s</tr>",
                    $initial,
                    Helpers::Reducer(
                        $currentValue,
                        fn($init, $v) => sprintf("%s<th class='text-black-50' scope='row'>%s</th>", $init, $v),
                        '<tr>'
                    )
                ),
                '<tbody>'
            )
        );
        return $html;
    }

    /**
     * @param string $templateFileName
     * @param array $context
     * @return string
     * @throws Exception
     */
    protected static function render(string $templateFileName, array $context = []): string
    {
        return self::viewFileAsString($templateFileName, true, $context);
    }

    /**
     * @param string $templateFileName
     * @param bool $ob
     * @param array $context
     * @return string
     */
    protected static function viewFileAsString(string $templateFileName, bool $ob = false, array $context = []): string
    {
        $patchToViewers = self::getPathToViewers();
        $pathToFile = "$patchToViewers$templateFileName.php";
        !file_exists($pathToFile) &&
        die(print_r(["[$pathToFile]", "view {$templateFileName} not found!", __DIR__]));
        if ($ob) {
            ob_start();
            define('context', $context);
            include_once($pathToFile);
            return ob_get_clean() ?? '';
        }
        return Helpers::createStreamFromFile($pathToFile)->getContents();
    }

    /**
     * @return string
     */
    protected static function getPathToViewers(): string
    {
        return self::$pathToViewers;
    }

    /**
     * @param string $pathToViewers
     */
    protected static function setPathToViewers(string $pathToViewers): void
    {
        self::$pathToViewers = $pathToViewers;
    }
}