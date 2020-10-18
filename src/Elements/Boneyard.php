<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

class Boneyard extends AbstractElement
{
    public const REGEX = "/(^\/\*)|(\*\/\s*$)/";

    public function sanitize($line) {
        return str_replace(array('/*', '*/'), '', $line);
    }

    public function match($line)
    {
        return preg_match(self::REGEX, $line);
    }

    public function __toString()
    {
        // Boneyard is ignored in the output
        return '';
    }
}
