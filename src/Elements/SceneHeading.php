<?php

/** @noinspection SpellCheckingInspection */

namespace Fountain\Elements;

use Fountain\AbstractElement;
use Fountain\Illuminate\Str;

/**
 * Scene Heading
 * A Scene Heading is any line that has a blank line following it.
 */
class SceneHeading extends AbstractElement
{
    public const REGEX = '/^(INT|EXT|EST|I\\/??E)[\\.\\-\\s]/i';

    public function __toString()
    {
        $text = $this->getText();
        $className = Str::kebab($this->getClass());

        // Scene headings can contain options numbers
        // Let's remove these and add them to the HTML element
        if (preg_match('/#.*#/i', $text, $numbering)) {
            $line = preg_replace('/#.*#/i', '', $text);
            $anchor = preg_replace('/#/i', '', $numbering[0]);

            return '<h3 class="'.$className.'" id="'.$anchor.'">'.$line.'</h3>';
        }

        return '<h3 class="'.$className.'">'.trim($text).'</h3>';
    }

    public function forcedHeading($line)
    {
        return preg_match('/^\\.[^.]/', $line);
    }

    public function match($line)
    {
        $forced_scene_heading = $this->forcedHeading($line);

        // strict headings allow all scene_headings
        // causes a conflict in french where sentences start with "Est-ce que c"
        // to fix this: prefix sentences with an exclamation point `!`
        $scene_heading = preg_match(self::REGEX, $line, $scene_heading_matches);

        return $forced_scene_heading || $scene_heading;
    }

    public function sanitize($line)
    {
        // remove the prefix
        if ($this->forcedHeading($line)) {
            $prefix_length = 1;
        } else {
            // you can optionally remove the prefix INT or EXT with:
            // $prefix_length = strlen($scene_heading_matches[0]);
            $prefix_length = 0;
        }

        $line_without_prefix = substr($line, $prefix_length);

        return trim($line_without_prefix);
    }
}
