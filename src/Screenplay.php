<?php

namespace Fountain;

class Screenplay
{
    /**
     * FountainParser
     * @var $elements
     */
    protected $elements;

    /**
     * FountainTags
     * @var $tags
     */
    protected $tags;

    /**
     * Fountain constructor.
     */
    public function __construct()
    {
        $this->elements = new FountainParser();
        $this->tags = new FountainTags();
    }

    /**
     * Parse Fountain text into HTML
     *
     * @param $text string input document
     * @return string HTML
     */
    public function parse(string $text)
    {
        $elements = $this->elements($text);
        return $this->tags($elements);
    }

    /**
     * Parse Fountain text into FountainElements
     *
     * @param $text string input document
     * @return mixed FountainElementsCollection
     */
    public function elements(string $text)
    {
        return $this->elements->parse($text);
    }

    /**
     * Parse FountainElements into HTML
     *
     * @param $elements
     * @return string HTML
     */
    public function tags($elements)
    {
        return $this->tags->parse($elements);
    }
}
