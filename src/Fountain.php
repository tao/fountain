<?php

namespace Fountain;

class Fountain
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
        return $this->tags->parse($elements);
    }

    /**
     * Parse Fountain text into FountainElements
     *
     * @param $text string input document
     * @return mixed FountainElements
     */
    public function elements(string $text)
    {
        return $this->elements->parse($text);
    }
}
