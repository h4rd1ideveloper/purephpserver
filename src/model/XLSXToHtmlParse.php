<?php

namespace App\model;

use App\assets\lib\Dao;

/**
 * Class XLSXToHtmlParse
 */
class XLSXToHtmlParse extends Dao
{
    /**
     * XLSXToHtmlParse constructor.
     * @param bool $production
     */
    public function __construct($production = false)
    {
        if ($production) {
            parent::__construct(
                PRODUCTION_DB_USER,
                PRODUCTION_DB_PASS,
                PRODUCTION_DB_NAME,
                PRODUCTION_DB_TYPE,
                null,
                PRODUCTION_DB_HOST
            );
        } else {
            parent::__construct(DB_HOST, DB_USER,DB_PASS,DB_NAME);
        }
        parent::connect();
    }
}