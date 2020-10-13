<?php

namespace App\Fountain;

use App\Fountain\CommonMark\Block\Element\IndentedContent;
use App\Fountain\CommonMark\Block\Parser\IndentedContentParser;
use App\Fountain\CommonMark\Block\Renderer\IndentedContentRenderer;
use App\Fountain\FountainScribe as Scribe;
use ErrorException;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Parser\ListParser;
use League\CommonMark\Block\Renderer\ListBlockRenderer;
use League\CommonMark\Block\Renderer\ListItemRenderer;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;

class FountainTags
{
    protected $scribe;

    /**
     * CommonMark Environment
     * @var $environment
     */
    protected $environment, $dialogEnvironment;

    /**
     * CommonMark default markdown converter
     * @var $converter
     */
    protected $commonmark;

    /**
     * CommonMark inline markdown only converter
     * @var $inline
     */
    protected $inline;

    /**
     * CommonMark inline markdown only converter
     * @var $dialog
     */
    protected $dialog;

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

        $config = [
            'enable_em' => true,            // enable em
            'enable_strong' => true,        // enable bold
            'use_asterisk' => true,         // use *emphasis*
            'use_underscore' => false,      // disabled _emphasis_, as we could use it for underline
        ];

        // Create a new, empty environment
        $this->environment = new Environment();
        $this->dialogEnvironment = new Environment();

        // Add this extension
        $this->environment->addExtension(new InlinesOnlyExtension());
        $this->environment->addExtension(new StrikethroughExtension());
        $this->environment->addExtension(new SmartPunctExtension());
        $this->environment->addExtension(new AutolinkExtension());

        // Instantiate the converter engine and start converting some Markdown!
        $this->inline = new CommonMarkConverter($config, $this->environment);

        // Create dialog converter
        $this->dialogEnvironment->addExtension(new InlinesOnlyExtension());
        $this->dialogEnvironment->addExtension(new StrikethroughExtension());
        $this->dialogEnvironment->addExtension(new SmartPunctExtension());
        $this->dialogEnvironment->addExtension(new AutolinkExtension());
        $this->dialogEnvironment->addBlockParser(new ListParser(), 10);
        $this->dialogEnvironment->addBlockRenderer(ListBlock::class, new ListBlockRenderer(), 0);
        $this->dialogEnvironment->addBlockRenderer(ListItem::class, new ListItemRenderer(), 0);

        // This adds our IndentedContentParser class one weight lighter than
        // \League\CommonMark\Block\Parser\IndentedCodeParser so that we can render
        // indented content before the latter parser gets to it, thus preventing it from
        // matching. This allows us to handle indented code better in the Footnotes.
        $this->dialogEnvironment->addBlockParser(new IndentedContentParser(), -99);
        $this->dialogEnvironment->addBlockRenderer(IndentedContent::class, new IndentedContentRenderer());

        // Instantiate markdown converter for dialog
        $this->dialog = new CommonMarkConverter($config, $this->dialogEnvironment);

        // Instantiate full markdown converter
        $this->commonmark = new CommonMarkConverter($config);
    }

    /**
     * Parse the Element Collection and render the correct HTML markup for each type
     *
     * @param $elements FountainElementCollection
     * @return string HTML
     */
    public function parse($elements)
    {
        // create a new content holder
        $this->html = '';

        $elementTypes = (new FountainType())->provideRoles();

        // parse each element type returned from the fountain parser
        foreach ($elements->elements as $key => $value) {
            try {
                $textValue = $value->text;

                // evaluate the value type, and parse each value
                foreach($elementTypes as $element) {
                    $elementName = (new \ReflectionClass($element))->getShortName();

                    if ($elementName === $value->type) {
                        // should the text also go through the common mark parser?
                        if ($element->shouldParseMarkdown) {
                            $textValue = $this->{$element->markdownParserType}->convertToHtml($textValue);
                        }

                        // parse inline notes
                        $textValue = $this->scribe->parseInlineNotes($textValue);
                        $textValue = $this->scribe->parseUnderlines($textValue);
                        $textValue = $this->scribe->cleanHTML($textValue);

                        // render element as HTML
                        $this->html .= ($key === 0 ? '' : PHP_EOL);
                        $this->html .= $element->render($textValue);
                        continue 2; // break outer loop
                    }
                }

            } catch (ErrorException $e) {
                dd($e);
            }
        }

        // default return without padding
        return '<article class="fountain">'.PHP_EOL.$this->html.PHP_EOL.'</article>';
    }
}
