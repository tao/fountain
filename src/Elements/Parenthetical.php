<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

/**
 * Parenthetical
 * These follow a Character or Dialogue element, and are wrapped in ().
 *
 * These are useful to describe what is happening behind the scenes,
 * for example: (Cough.) (Inaudible) (Tape ends.)
 */
class Parenthetical extends AbstractElement
{
    public const REGEX = "/^\s*\(/";

    public function match($line) {
       return preg_match(self::REGEX, trim($line));
    }

    public function sanitize($line)
    {
        return trim($line);
    }
}
