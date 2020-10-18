<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

/**
 * Characters
 * Match CHARACTERS or any line starting with @
 * Allow indents and whitespace in the beginning
 */
class Character extends AbstractElement
{
    public $dual_dialog = false;

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

    public function __toString()
    {
        $character = $this->getText();

        if (isset($this->dual_dialog) && $this->dual_dialog === true) {
            $character .= " (DUAL)";
        }

        return "<h4 class='character'>{$character}</h4>";
    }
}
