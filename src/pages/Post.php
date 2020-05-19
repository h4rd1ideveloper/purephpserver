<?php

use App\lib\Components as _;
use App\lib\Helpers;


function Post_create($ctx = [])
{
    $title = $ctx['title'] ?? 'TinyMCE Quick Start Guide';
    return _::headerHTML([
            'title' => 'Create a new Post',
            'raw' => /**Head */
                <<<'HTML'
                
                        <script src="https://cdn.tiny.cloud/1/h7u8n7d3a8wepyxkmd6my3ffxecb65idvd9w6b2fm28p2osd/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
                        <script> tinymce.init({selector: "#mytextarea",language: "pt_BR"});</script>

                HTML
        ]) .
        /**Body */
        <<<HTML
                <h1>$title</h1>
                <form id='post_form' method='post'>
                    <textarea id='mytextarea'></textarea>
                    <button id='post_save' class='btn btn-block btn-primary'>save</button>
                </form>
        HTML .
        /**Scripts Injection */
        _::scripts([
            Helpers::baseURL() . 'src/pages/js/post_dependencies.js',
            Helpers::baseURL() . 'src/pages/js/axios.js'
        ]) .
        /**Custom scripts */
        <<<HTML
                <script type='text/javascript'>
                    (() =>{
                        el()
                        .id('post_form')
                        .addEventListener('submit', create_post(
                            el().id('mytextarea'),
                            el().id('post_save')
                        ))
                    })()
                </script>
        HTML .
        _::closeView();
}