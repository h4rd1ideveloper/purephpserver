<?php

namespace App\assets\lib;


use App\Database\Bridge\QueryBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryBuilderTest
 * @package App\assets\lib
 */
class QueryBuilderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testQueryUpdate()
    {
        self::assertEquals(
            "UPDATE users SET username = 'lucas' WHERE id = '1' ",
            QueryBuilder::queryUpdate('users', ['username' => 'lucas'], ['id' => '1'])
        );
    }

    /**
     * @throws Exception
     */
    public function testQuerySelect()
    {
        self::assertEquals(
            "SELECT * FROM users",
            QueryBuilder::querySelect(null, 'users')
        );
    }

    /**
     *
     * @throws Exception
     */
    public function testQueryInsert()
    {
        self::assertEquals(
            "INSERT INTO users ( username, password ) VALUES ( 'yan', '123' )",
            QueryBuilder::queryInsert('users', ['username' => 'yan', 'password' => 123])
        );
    }

}
