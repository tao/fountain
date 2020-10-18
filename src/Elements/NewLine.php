<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

class NewLine extends AbstractElement
{
    public const REGEX = "/^\s*$/";

    public function match($line) {
        if ($line === "") return true;
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
