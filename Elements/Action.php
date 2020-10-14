<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Action
 * An action element that is not dialog: when a character performs an action
 * We only mark-up Actions if they are prefixed with an exclamation point !
 *
 * WARNING: Action was the default type in Fountain, however this
 *          has been replace with Dialogue for our use case.
 */
class Action extends AbstractElement
{
    public $parseEmphasis = true;

    public const REGEX = "/^!/";

    public function match($line) {
       return preg_match(self::REGEX, trim($line));
    }

    function sanitize($line)
    {
        // Find and return the text of the action without !
        preg_match("/^\s*!{1}(.*)/", $line, $matches);
        return count($matches) ? trim($matches[1]) : trim($line);
    }
}
