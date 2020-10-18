<?php

namespace Fountain\Elements;

use Fountain\AbstractElement;

/**
 * Lyrics
 * You create a Lyric by starting with a line with a tilde ~.
 */
class Lyrics extends AbstractElement
{
    public const REGEX = "/^(\s+)?~.*/";

    public function match($line) {
       return preg_match(self::REGEX, $line);
    }

    public function sanitize($line)
    {
        // Find and return the text of the lyrics without ~
        preg_match("/^(\s*)?~{1}(.*)/", trim($line), $matches);
        return trim($matches[2]);
    }
}
