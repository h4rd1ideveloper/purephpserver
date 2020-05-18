<?php


namespace App\pages\components;


use App\lib\Components as _;

class Post
{
    /**
     * @param array $ctx
     * @return string
     */
    public static function create($ctx = [])
    {
        $title = $ctx['title'] ?? 'TinyMCE Quick Start Guide';
        return _::headerHTML([
                'title' => 'Create a new Post',
                'raw' => '
        <script 
            src="https://cdn.tiny.cloud/1/h7u8n7d3a8wepyxkmd6my3ffxecb65idvd9w6b2fm28p2osd/tinymce/5/tinymce.min.js" 
            referrerpolicy="origin"
        >
        </script>
        <script>
            tinymce.init({
                selector: "#mytextarea",
                language: "pt_BR"
            });
        </script>
    '
            ]) .
            _::pipe("
                <h1>$title</h1>
                <form method='post'>
                    <textarea id='mytextarea'></textarea>
                 </form>
            ") .
            _::scripts() .
            _::closeView();
    }
}