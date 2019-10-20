<?php

namespace App\assets\lib;

use Exception;
use PDO;
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
        self::assertEquals("UPDATE users SET  name = 'lucas'  , data = '{\"field\":\"value\"}'  WHERE  _id = '1' ", QueryBuilder::queryUpdate('users', array('name' => 'lucas', 'data' => '{"field":"value"}'), array('_id' => '1')));
    }

    /**
     * @throws Exception
     */
    public function testQuerySelect()
    {
        $_db = new Dao('localhost', 'root', '', 'app');
        $_db->connect();
        $sql = QueryBuilder::querySelect($_db->getDB(), 'users u', '*', 'inner join chat c on c.from_user_id = u._id', "u.name is not null ", 'u.name DESC', '3');
        $sql->execute();
        $json = Helpers::toJson($sql->fetchAll(PDO::FETCH_ASSOC));
        self::assertEquals('', $json);
    }

    /**
     *
     */
    public function testQueryInsert()
    {

    }
}
