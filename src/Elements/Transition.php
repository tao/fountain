<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

class Transition extends AbstractElement
{
    public const REGEX = "/^(\s+)?>.*/";

    protected $transitions = array(
        'CUT TO:',
        'FADE OUT.',
        'SMASH CUT TO:',
        'CUT TO BLACK.',
        'MATCH CUT TO:'
    );

    public function match($line) {
        if (in_array(trim($line), $this->transitions)) {
            return true;
        }

        return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        return ltrim($line, '>');
    }
}
