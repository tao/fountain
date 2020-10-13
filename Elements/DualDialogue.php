<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

class DualDialogue extends AbstractElement
{
    /**
     * Check whether this is a dual dialog line
     */
    public const REGEX = "/\^\s*$/";

    public function match($line) {
        return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        return $line;
    }

    public function render($line)
    {
        return $line;
    }
}
