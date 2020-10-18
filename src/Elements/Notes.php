<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

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
    public $parseEmphasis = true;
    public const REGEX = "/^\s*\[{2}\s*([^\]\n])+\s*\]{2}\s*$/";

    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    function sanitize($line)
    {
        return trim(str_replace(array('[[', ']]'), '', $line));
    }

    public function __toString()
    {
        return '<p class="notes"><em>['.$this->getText().']</em></p>';
    }
}
