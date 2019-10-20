<?php

class Components
{
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

    public static function table(array $data, $flag = false, $id = false, $tableClass = false)
    {
        if (count($data) > 1) {
            $html = sprintf('<table id="%s" class="table table-striped table-hover">
                    <thead>
                        <tr> ', $id ?? 'table-' . date('m_d_Y_h_i_s_a', time()));
            if ($flag) {
                foreach ($data[0] as $headers => $value) {
                    $html .= sprintf('<th scope="col">%s</th>', $headers);
                }
                $html .= '</tr></thead><tbody>';
                for ($i = 0; $i < count($data); $i++) {
                    $html .= '<tr>';
                    $index = 0;
                    foreach ($data[$i] as $key => $value) {
                        $html .= (0 === $index++) ?
                            sprintf('<th scope ="row" > %s</th >', $value)
                            :
                            sprintf('<td>%s</td>', $value);
                    }
                    $html .= '</tr >';
                }
                $html .= '</tbody></table>';
                echo $html;
            } else {
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
        }
    }

    public static function formXSLXtoTable(array $data, array $xlsx, array $source, string $title = ' Associe as colunas da planilha, com as suas respectivas colunas na tabela'): void
    {
        $html = sprintf('
            <div class="col-12 mb-5">
                <h3 class="h5 text-black-50">%s</h3>
            </div>',
            $title
        );
        $html .= '
            <div class="col-12 mx-auto">
                <form class="row" action="/consiliar" method="POST" id="toCheck" >';
        $html .= sprintf("<input name='source' type='hidden' value='%s' />", json_encode(array_filter($source, function ($row) {
            foreach ($row as $value) {
                if ($value != "" || $value != null) {
                    return true;
                } else {
                    continue;
                }
            }
            return false;
        }), true));
        foreach ($data as $key => $value) {
            $html .= sprintf(
                '
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <label class="text-justify text-dark" for="%s">%s</label>
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