<?php

namespace App\Fountain;

use Illuminate\Support\Str;

abstract class AbstractElement
{
    public $parseEmphasis = false;

    /**
     * @var string text value
     */
    protected $text = "";

    /**
     * @var string name of Child class
     */
    protected $type;

    /**
     * Store the name of the Child class
     *
     * AbstractElement constructor.
     */
    public function __construct()
    {
        $this->type = get_class($this);
    }

    /**
     * Get the name of the Child class with namespace
     *
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the short name of the Child class
     * @return string classname
     */
    public function getClass()
    {
        return substr(strrchr($this->type, '\\'), 1);
    }

    /**
     * Determine if the Element Types match
     *
     * @param $type
     * @return bool
     */
    public function is($type)
    {
        return $type === $this->type;
    }

    /**
     * Get the text value of the element
     *
     * @return mixed|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Append to the text value of the element
     *
     * @return mixed|string
     */
    public function appendText($text)
    {
        $this->text .= $text;
    }

    /**
     * Set the text value of the element
     *
     * @return mixed|string
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Create the FountainElement
     */
    public function create($text) {
        $this->text = $this->sanitize($text);
        return $this;
    }

    /**
     * Match an element with regex
     */
    abstract public function match($line);

    /**
     * Sanitize and clean text
     */
    abstract public function sanitize($line);

    /**
     * Render the element
     */
    public function __toString()
    {
        if ($this->text === '') {
            return '';
        }

        $className = Str::kebab($this->getClass());
        return "<p class='{$className}'>{$this->text}</p>";
    }
}
