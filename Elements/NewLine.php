<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

class NewLine extends AbstractElement
{
    /**
     * Check whether a code block starts or ends on this line
     */
    public const REGEX = "/^\s*$/";

    public function match($line) {
        if ($line === "") return true;
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
