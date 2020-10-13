<?php

namespace App\Fountain;

use App\Fountain\FountainElement;

/**
 * FountainElement
 * Based off the FastFountainParser.m
 *
 * https://github.com/alexking/Fountain-PHP
 *
 * @author Alex King (PHP port)
 * @author Nima Yousefi & John August (original Objective-C version)
 */
class FountainElementCollection
{

    public $elements;
    public $types;

    /**
     * Add and index the element
     * @param  FountainElement  $element
     */
    public function add_element(FountainElement $element)
    {
        // add to the element array
        $this->elements[] = $element;

        // add to the types array for quick searching
        $this->types[] = $element->type;
    }

    /**
     * Convenience function for creating and adding a FountainElement
     * @param  string  $type  Character, Dialog, etc.
     * @param  string  $text  Text for the element
     * @param  array  $extras  Additional properties
     */
    public function create_and_add_element($type, $text, $extras = array())
    {
        // create
        $element = new FountainElement($type, $text, $extras);

        // add to the collection
        $this->add_element($element);
    }

    /**
     * Find the most recent element by type
     * @param  string  $type  type of element
     * @return mixed    FountainElement or FALSE
     */
    public function &find_last_element_of_type($type)
    {
        $response = false;

        // reverse the index
        $types = array_reverse((array) $this->types, true);

        // find the last one
        $index = array_search($type, $types, true);

        // return if successful
        if ($index) {
            $response = $this->elements[$index];
        }

        return $response;
    }

    /**
     * Find the last element
     * @return mixed    FountainElement or FALSE
     */
    public function &last_element()
    {
        $response = false;

        if ($this->elements && $count = count($this->elements)) {
            $response = $this->elements[$count - 1];
        }

        return $response;
    }

    /**
     * Return the number of elements
     * @return int
     */
    public function count()
    {
        return ($this->elements) ? count($this->elements) : 0;
    }

    /**
     * Convert to string
     */
    public function __toString()
    {
        $string = "";

        foreach ((array) $this->elements as $element) {
            $string .= (string) $element."\n";
        }

        return $string;
    }

}
