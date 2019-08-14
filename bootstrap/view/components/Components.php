<?php

class Components
{
    public static function centredTextH1($text)
    {
        echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="h3 text-center text-black-50">%s </h1>
                </div>
            </div>
        ', $text);
    }

    public static function table(array $data)
    {
        $html = '<table class="table table-striped table-hover">
                    <thead>
                        <tr> ';
        for ($i = 0; $i < count($data); $i++) {
            if ($i === 0) {
                foreach ($data[$i] as $headers) {
                    $html .= sprintf('<th scope="col">%s</th>', $headers);
                }
                $html .= '</tr></thead><tbody>';
            } else {
                if ($data[$i][0] === '') {
                    continue;
                }
                $html .= '<tr>';
                foreach ($data[$i] as $key => $value) {
                    $html .= (0 === $key) ?
                        sprintf('<th scope ="row" > %s</th >', $value)
                        :
                        sprintf('<td>%s</td>', $value);

                }
                $html .= '</tr >';
            }
        }
        $html .= '</tbody></table>';
        echo $html;
    }

    public static function formXSLXtoTable(array $data, array $xlsx, array $source, string $title = ' Associe as colunas da planilha, com as suas respectivas colunas na tabela'): void
    {
        $html = sprintf('
            <div class="col-12 mb-5">
                <h3 class="h5">%s</h3>
            </div>',
            $title
        );
        $html .= '
            <div class="col-12 mx-auto">
                <form class="row" action="/consiliar" method="post" >';
        $html .= sprintf("<input name='source' type='hidden' value='%s' />", json_encode(array_filter($source, function ($row){
            foreach ($row as $value){
                if($value !=""|| $value !=null){
                    return true;
                }
                else{
                    continue;
                }
            }
            return false;
        }), true));
        foreach ($data as $key => $value) {
            $html .= sprintf(
                '
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <label for="%s">%s</label>
                        <select name="%s" class="form-control">
                ',
                $value, $value, $value
            );
            $html .= '<option selected class="text-muted" >Não utilizado</option>';
            foreach ($xlsx as $key => $value) {
                if ($value === '') continue;
                $html .= sprintf('<option value="%s">%s</option>', $value, $value);
            }
            $html .= '</select></div>';
        }
        $html .= ' <button type="submit" class="btn btn-outline-secondary btn-lg my-5">Conciliar</button>   </form>
                </div>';
        echo $html;
    }
}