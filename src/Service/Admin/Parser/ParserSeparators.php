<?php

namespace App\Service\Admin\Parser;

/**
 * Class contains parser separators characters
 *
 * @author Mateusz Mirosławski
 */
abstract class ParserSeparators
{
    /// Command value separator for normal query
    public const CVS = '|';

    /// Values separator
    public const VS = ',';

    /// Command value separator for multi query
    public const CVMS = '?';

    /// Commands separator for multi query
    public const CMS = '!';
    
    /// Cycle times separator
    public const CTTH = '!';
    
    public const CTVAL = '?';
    
    /// Cycle time Thread and value separator
    public const CTTHVAL = ':';
}
