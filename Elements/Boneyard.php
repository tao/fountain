<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

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

    public function render($line)
    {
        // Boneyard is ignored in the output
        return;
    }
}
