<?php

namespace App\Service\Admin\Parser;

/**
 * Class contains parser separators characters
 *
 * @author Mateusz Mirosławski
 */
abstract class ParserSeparators {
    
    /// Command value separator for normal query
    const cvS = '|';

    /// Values separator
    const vS = ',';

    /// Command value separator for multi query
    const cvMS = '?';

    /// Commands separator for multi query
    const cMS = '!';
    
    /// Cycle times separator
    const ctTh = '!';
    
    const ctVal = '?';
    
    /// Cycle time Thread and value separator
    const ctThVal = ':';
}
