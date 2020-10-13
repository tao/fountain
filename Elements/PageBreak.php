<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Page Break
 * A standard HTML page break element
 */
class PageBreak extends AbstractElement
{
    /**
     * Match any row with 3 dashes or equal signs
     */
    public const REGEX = "/^(-{3,})|(={3,})\s*$/";

    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        return $line;
    }

    public function render($line)
    {
        return '<hr />';
    }
}
