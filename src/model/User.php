<?php


namespace App\model;


use App\lib\Helpers;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 * @method static findOrFail(int $int)
 * @method skip(int $int)
 * @method static create(string[] $array)
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

    /**
     * @param int $skip
     * @param int $limit
     * @param string[] $skipFields
     * @return array
     */
    public function getUsersFields($skip = 0, $limit = 100, $skipFields = ['user_id', 'password'])
    {
        return Helpers::Map(
            (new User)->skip($skip)->limit($limit)->get()->toArray(),
            fn($v) => Helpers::Filter(
                $v,
                fn($value, $key) => $value && !in_array($key, $skipFields)
            )
        );
    }
}