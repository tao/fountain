<?php

namespace App\Fountain\CommonMark\Block\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

/**
 * Indented content CommonMark renderer.
 */
class IndentedContentRenderer implements BlockRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(
        AbstractBlock $block,
        ElementRendererInterface $htmlRenderer,
        bool $inTightList = false
    ) {
        // This just renders the children in place, without a containing element.
        return $htmlRenderer->renderBlocks($block->children());
    }
}