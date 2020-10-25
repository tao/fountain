<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

/**
 * Dialogue
 * Dialogue is any text following a Character or Parenthetical element.
 */
class Dialogue extends AbstractElement
{
    public $parseEmphasis = true;

    public function match($line)
    {
        return $line;
    }

    public function sanitize($line)
    {
        // Sometimes, you may really want to start normal dialogue with brackets,
        // you can prefix this with a backslash to override the parenthesis.
        return ltrim($line, '\\');
    }
}
