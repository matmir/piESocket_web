<?php

namespace App\Service\Admin\Parser;

/**
 * Class contains parser reply codes
 *
 * @author Mateusz Mirosławski
 */
abstract class ParserReplyCodes
{
    public const OK = 0;
    public const NOK = -1;

    public const NOT_EXIST = 5;
    public const WRONG_VALUE = 6;
    public const WRONG_TAG_TYPE = 7;
    public const WRONG_TAG_AREA = 8;
    public const WRONG_ADDR = 9;

    public const INTERNAL_ERR = 20;

    public const SQL_ERROR = 50;

    public const UNKNOWN_CMD = 99;
}
