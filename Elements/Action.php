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
    public $shouldParseMarkdown = true;

    /**
     * If there aren't any preceding newlines, and there's a "!"
     * Additional action lines will be appended later.
     */
    public const REGEX = "/^!/";

    public function match($line) {
       return preg_match(self::REGEX, trim($line));
    }

    /**
     * Find and return the text of the action without !
     */
    function sanitize($line)
    {
        preg_match("/^\s*!{1}(.*)/", $line, $matches);
        return count($matches) ? trim($matches[1]) : trim($line);
    }

    public function render($line)
    {
        $line = $this->sanitize($line);
        return '<p>'.$line.'</p>';
    }
}
