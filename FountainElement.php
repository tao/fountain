<?php

namespace App\Fountain;

/**
 * FountainElement
 * Based off the FastFountainParser.m
 *
 * https://github.com/alexking/Fountain-PHP
 *
 * @author Alex King (PHP port)
 * @author Nima Yousefi & John August (original Objective-C version)
 */
class FountainElement
{
    public $type;
    public $text;

    /**
     * Construct a new FountainElement
     * @param  string  $type  Character, Dialog, etc.
     * @param  string  $text  Text for the element
     * @param  array  $extras  Additional properties
     */
    public function __construct($type, $text, $extras = array())
    {
        // assign the type and text
        $this->type = $type;
        $this->text = $text;

        // assign the extras
        if (count($extras)) {
            foreach ((array) $extras as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Convert to String
     * @return string
     */
    public function __toString()
    {
        $string = '';
        $string .= strtoupper($this->type).":".$this->text;

        if (isset($this->dual_dialog) && $this->dual_dialog === true) {
            $string .= "(DUAL)";
        }

        return $string;
    }
}
