<?php

namespace App\assets\lib;

use Exception;
use Lib\Helpers;
use PHPUnit\Framework\TestCase;

/**
 * Class HelpersTest
 * @package App\assets\lib
 */
class HelpersTest extends TestCase
{
    /**
     * testToJson
     * @throws Exception
     * @throws Exception
     */
    public function testToJson()
    {
        $this->assertEquals(
        /** @lang JSON */ '{"field":1}',
            Helpers::toJson(array('field' => 1))
        );
    }

    /**
     * testShowErrors
     * @throws Exception
     * @throws Exception
     */
    public function testShowErrors()
    {
        /* @see   error_reporting {@error_reporting(E_ALL | E_STRICT);} */
        Helpers::showErrors();
        $this->assertEquals(true, (bool)(error_reporting() === E_ALL || error_reporting() === E_STRICT));
    }

    /**
     *
     * @throws Exception
     */
    public function testInsertIfNotExist()
    {
        $toCheck = array();
        Helpers::insertIfNotExist(1, $toCheck);
        Helpers::insertIfNotExist(1, $toCheck);
        $this->assertEquals(true, (bool)(count($toCheck) === 1), 'Check if insert only once each value');
    }

    /**
     * @throws Exception
     */
    public function testObjectKeys()
    {
        self::assertEquals("field", (Helpers::objectKeys(array("field" => "value", "field2" => "value2")))[0]);
    }

    /**
     * @throws Exception
     * @see json_decode()
     * @see Helpers::jsonToArray()
     */
    public function testJsonToArray()
    {
        $arr = Helpers::jsonToArray(/** @lang JSON */ '{"field":"value"}');
        self::assertArrayHasKey("field", $arr);
    }

    /**
     *
     * @throws Exception
     */
    public function testGetRowsById()
    {
        $rows = array(
            "COD" => "001",
            "DESC" => "AnyValue",
            "ANY" => "AnyThing",
        );
        $ids = array("COD", "DESC");
        $filtered = Helpers::getRowsById($ids, $rows);
        self::assertArrayHasKey("COD", $filtered);//Check key
        self::assertEquals("001", $filtered["COD"]);// Check Value
    }

    /**
     *
     * @throws Exception
     */
    public function testStringIsOk()
    {
        self::assertEquals(true, Helpers::stringIsOk(array('', 1, 'valid string')[2]));
    }

    /**
     *
     * @throws Exception
     */
    public function testObjectValues()
    {
        self::assertEquals("value2", (array("field" => "value", "field2" => "value2"))["field2"]);
    }

    /**
     *
     * @throws Exception
     */
    public function testIsSQLInjection()
    {
        self::assertEquals(true, Helpers::isSQLInjection("drop database"), "Test if Contain SQLInjection on injection string");
        self::assertEquals(true, Helpers::isSQLInjection("drop user like true"), "Test if Contain SQLInjection on injection string");
        self::assertEquals(false, Helpers::isSQLInjection("06af30a8554e7fea089778c498f663ed"), "Test if Contain SQLInjection on not injection string hashed MD5");
        self::assertEquals(false, Helpers::isSQLInjection("Fullstack666poo"), "Test if Contain SQLInjection on not injection string not hashed MD5");
    }

    /**
     *
     * @throws Exception
     */
    public function testReducer()
    {
        $count = Helpers::Reducer(array(1, 2, 3), function ($initialValue, $current, $index) {
            return $initialValue + $current + $index;
        }, 0);
        self::assertEquals(9, $count);
        $entries = Helpers::Reducer(array("field1" => 1, "field2" => 2, "field3" => true), function ($initialValue, $current, $index) {
            $initialValue[] = array($index, $current);
            return $initialValue;
        }, array());
        self::assertEquals(/**@lang JSON */ '[["field1",1],["field2",2],["field3",true]]', Helpers::toJson($entries));
    }


    /**
     *
     * @throws Exception
     */
    public function testMap()
    {
        self::assertEquals(
            '<p>Hello</p>',
            Helpers::Map(['Hello', 'Word'], static function ($value) {
                return "<p>$value</p>";
            })
            [0]
        );

    }

    /**
     *
     * @throws Exception
     */
    public function testEntries()
    {
        $arr = [
            "profile" => ["id" => 1, "name" => "yan", "picture" => "urlToPicture"],
            "account" => [
                "uc" => 123, "hidden" => [1.55, 99, 100]
            ],
            "_UMD" => 55,
            19,
            22
        ];
        $keysAndValues = Helpers::Entries($arr);
        $keys = Helpers::objectKeys($arr);
        for ($i = 0; $i < count($keysAndValues); $i++) {
            self::assertEquals($keys[$i], $keysAndValues[$i][0], "objectKeys[...keys] = Entries[...keysAndValues][0]");
        }
    }

}
