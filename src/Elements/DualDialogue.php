<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

/**
 * DualDialog
 * Check whether this is a dual dialog line,
 * this element is not included in the render list
 */
class DualDialogue extends AbstractElement
{
    public const REGEX = "/\^\s*$/";

    public function match($line) {
        return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        // remove dual dialog mark
        $line = preg_replace("/\^/i", "", $line);
        return trim($line);
    }
}
