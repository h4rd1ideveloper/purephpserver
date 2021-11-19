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
    protected string $table = 'users';
    /**
     * @var string[]
     */
    protected array $fillable = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address'
    ];
}