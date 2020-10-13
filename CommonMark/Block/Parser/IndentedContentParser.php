<?php

namespace App\Fountain\CommonMark\Block\Parser;

use App\Fountain\CommonMark\Block\Element\IndentedContent;
use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

/**
 * Indented content CommonMark parser.
 */
class IndentedContentParser implements BlockParserInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\CommonMark\Block\Parser\IndentedCodeParser::parse()
     *   Identical to this method other than the block added at the end, i.e.
     *   new IndentedCode() -> new IndentedContent()
     */
    public function parse(ContextInterface $context, Cursor $cursor): bool {
        if (!$cursor->isIndented()) {
            return false;
        }

        if ($context->getTip() instanceof Paragraph) {
            return false;
        }

        if ($cursor->isBlank()) {
            return false;
        }

        $cursor->advanceBy(Cursor::INDENT_LEVEL, true);
        $context->addBlock(new IndentedContent());

        return true;
    }
}