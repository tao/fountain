<?php

namespace App\Fountain\Elements;

use App\Fountain\AbstractElement;

/**
 * Dialogue
 * Dialogue is any text following a Character or Parenthetical element
 *
 * WARNING: Fountain modified to return Dialogue as the default text type.
 */
class Dialogue extends AbstractElement
{
    public $shouldParseMarkdown = true;
    public $markdownParserType = 'dialog';

    public function match($line) {
        return $line;
    }

    public function sanitize($line)
    {
        return trim($line);
    }

    public function render($line)
    {
        $line = $this->sanitize($line);
        return '<p class="dialogue">' . $line . '</p>';
    }
}
