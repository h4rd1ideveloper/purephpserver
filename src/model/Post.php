<?php


namespace App\model;


/**
 * Class Post
 * @package App\model
 */
class Post
{
    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var string[]
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address'
    ];
}