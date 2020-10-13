<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Notes
 * A Note is created by enclosing some text with [[ double brackets ]].
 * Notes can be inserted between lines, or in the middle of a line.
 *
 * These can be used in translations when we need to keep track of changes,
 * or provide suggestions for translations that may not be 100% correct.
 * These will not be show on the website.
 */
class Notes extends AbstractElement
{
    public const REGEX = "/^\s*\[{2}\s*([^\]\n])+\s*\]{2}\s*$/";

    /**
     * Capture [[ notes ]]
     */
    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    /**
     * Trim whitespace and [[ ]]
     */
    function sanitize($line)
    {
        return trim(str_replace(array('[[', ']]'), '', $line));
    }

    public function render($line)
    {
        return '<p class="note"><em>['.$line.']</em></p>';
    }
}
