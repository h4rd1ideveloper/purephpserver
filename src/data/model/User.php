<?php


namespace App\data\model;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 * @method static findOrFail(int $int)
 * @method skip(int $int)
 * @method static create(array $array):User
 * @method static where($key, $value)
 * @method static whereId(int $id)
 */
class User extends Model
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