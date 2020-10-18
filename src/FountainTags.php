<?php

namespace Fountain;

use Fountain\FountainScribe as Scribe;

class FountainTags
{
    /**
     * @var FountainScribe
     */
    protected $scribe;

    /**
     * Html container
     */
    private $html;

    /**
     * FountainScribe constructor.
     */
    public function __construct()
    {
        $this->scribe = new Scribe();
    }

    /**
     * Parse the Element Collection and render the correct HTML markup for each type
     *
     * @param $elements FountainElementCollection
     * @return string HTML
     */
    public function parse(FountainElementCollection $elements)
    {
        // create a new content holder
        $this->html = '';

        // get a list of elements to process
        $elementTypes = (new FountainType())->provideRoles();

        // parse each element type returned from the fountain parser
        foreach ($elements->elements as $key => $value) {
            $markdown = $value;

            // evaluate the value type, and parse each value
            foreach($elementTypes as $element) {
                if ($element->getType() === $value->getType()) {
                    // clean html
                    $markdown = $this->scribe->cleanHTML($markdown);

                    // parse additional emphasis
                    if ($element->parseEmphasis) {
                        $markdown = $this->scribe->parseInlineNotes($markdown);
                        $markdown = $this->scribe->parseUnderlines($markdown);
                        $markdown = $this->scribe->parseEmphasis($markdown);
                    }

                    // render element as HTML
                    $this->html .= ($key === 0 ? '' : PHP_EOL);
                    $this->html .= $markdown;
                    continue 2; // break outer loop
                }
            }
        }

        // default return without padding
        return '<article class="fountain">'.PHP_EOL.$this->html.PHP_EOL.'</article>';
    }
}
