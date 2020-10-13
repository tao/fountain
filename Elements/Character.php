<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

class Character extends AbstractElement
{
    /**
     * Match CHARACTERS or any line starting with @
     * Allow indents and whitespace in the beginning
     */
    public const REGEX = "/^((\s*)[A-Z@]((([^a-z`]+)(\s?\(.*\))?))|(@.+))$/";

    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        // (remove @ prefix)
        $line = ltrim($line, '@');
        // remove parenthesis, this is added separately in the parser
        $line = preg_replace("/\(.*\)/i", "", $line);
        // response
        return $line;
    }

    public function render($line)
    {
        return "<h4 class='character'>{$line}</h4>";
    }
}
