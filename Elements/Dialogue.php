<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Dialogue
 * Dialogue is any text following a Character or Parenthetical element
 */
class Dialogue extends AbstractElement
{
    public $parseEmphasis = true;

    public function match($line) {
        return $line;
    }

    public function sanitize($line)
    {
        return $line;
    }
}
