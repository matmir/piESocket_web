<?php

namespace App\Service\Admin\Parser;

/**
 * Class contains parser reply codes
 *
 * @author Mateusz Mirosławski
 */
abstract class ParserReplyCodes {
    
    const OK=0;
    const NOK=-1;

    const NOT_EXIST=5;
    const WRONG_VALUE=6;
    const WRONG_TAG_TYPE=7;
    const WRONG_TAG_AREA=8;
    const WRONG_ADDR=9;

    const INTERNAL_ERR=20;

    const SQL_ERROR=50;

    const UNKNOWN_CMD=99;
}
