<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Parenthetical
 * These follow a Character or Dialogue element, and are wrapped in ().
 *
 * These are useful to describe what is happening behind the scenes,
 * for example: (Cough.) (Inaudible) (Tape ends.)
 */
class Parenthetical extends AbstractElement
{
    /**
     * Match (Parenthesis)
     */
    public const REGEX = "/^\s*\(/";

    public function match($line) {
       return preg_match(self::REGEX, trim($line));
    }

    public function sanitize($line)
    {
        return $line;
    }

    public function render($line)
    {
        return '<p class="parenthesis"><em>'.$line.'</em></p>';
    }
}
