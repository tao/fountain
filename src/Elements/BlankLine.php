<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

class BlankLine extends AbstractElement
{
    public const REGEX = "/^(\s{2,})$/";

    public function match($line) {
        return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        return $line;
    }

    public function __toString()
    {
        return '';
    }
}
