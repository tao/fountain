<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Code
 * Allow code blocks as supported in markdown
 */
class Code extends AbstractElement
{
    /**
     * Check whether a code block starts or ends on this line
     */
    public const REGEX = "/^```/";

    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    public function sanitize($line) {
        return str_replace("```", '', $line);
    }

    public function render($line)
    {
        return '<p class="code">'.$line.'</p>';
    }
}
