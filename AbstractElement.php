<?php

namespace App\Fountain;

abstract class AbstractElement
{
    public $shouldParseMarkdown = false;
    public $markdownParserType = 'inline';

    /**
     * Match an element with regex
     */
    abstract public function match($line);

    /**
     * Sanitize and clean text
     */
    abstract public function sanitize($line);

    /**
     * Render the element as HTML
     */
    abstract public function render($line);
}
