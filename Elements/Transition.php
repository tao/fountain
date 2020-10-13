<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

class Transition extends AbstractElement
{
    /**
     * Match transition types
     */
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
        // (remove > prefix)
        $line = ltrim($line, '>');
        return $line;
    }

    public function render($line)
    {
        return '<p class="transition">'.$line.'</p>';
    }
}
