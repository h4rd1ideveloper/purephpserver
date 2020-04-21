<?php


namespace App\model;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 * @method static findOrFail(int $int)
 * @method skip(int $int)
 */
class User extends Model
{
    protected $table = 'users';


}