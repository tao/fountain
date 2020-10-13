<?php

namespace App\Fountain;

use App\Fountain\CommonMark\Block\Element\IndentedContent;
use App\Fountain\CommonMark\Block\Parser\IndentedContentParser;
use App\Fountain\CommonMark\Block\Renderer\IndentedContentRenderer;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Parser\ListParser;
use League\CommonMark\Block\Renderer\ListBlockRenderer;
use League\CommonMark\Block\Renderer\ListItemRenderer;
use League\CommonMark\Block\Renderer\ParagraphRenderer;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;

class FountainScribe
{

    /**
     * Inline Notes
     * Process all inline notes in a line
     */
    function parseInlineNotes($line)
    {
        // inline notes
        while (preg_match("/(\[\[.*?\]\])/", $line)) {
            $line = $this->handleInlineNotes($line);
        }

        return $line;
    }

    /**
     * Inline Notes
     */
    function handleInlineNotes($line)
    {
        // get the parts before and after [[note]] in the line of text
        list($before, $text) = preg_split("/\[\[/", $line, 2);
        list($note, $after) = preg_split("/\]\]/", $text, 2);

        $inline = '<span class="note"><em>['.$note.']</em></span>';

        return $before.$inline.$after;
    }

    /**
     * Underline Text
     * Process all underlined text in a line
     */
    function parseUnderlines($line)
    {
        // inline notes
        while (preg_match("/(_.*?_)/", $line)) {
            $line = $this->handleInlineUnderline($line);
        }

        return $line;
    }

    /**
     * Inline Underlines
     */
    function handleInlineUnderline($line)
    {
        // get the parts before and after _underline_ in the line of text
        list($before, $text) = preg_split("/_/", $line, 2);
        list($note, $after) = preg_split("/_/", $text, 2);

        $underline = '<span class="underline">'.$note.'</span>';

        return $before.$underline.$after;
    }

    /**
     * Remove empty paragraph tags
     *
     * This occurs when list items are in dialog text,
     * As we want to preserve line spacing in poetic dialog: 1992_0409
     *
     * When list items are present in dialog, the markdown converter wraps them in empty paragraph tags
     * So this method simply removes these empty tags
     */
    public function cleanHTML($html)
    {
        $html = str_replace('<p><ul>', '<ul>', $html);
        $html = str_replace('</ul></p>', '</ul>', $html);
        $html = str_replace('<p><ol>', '<ol>', $html);
        $html = str_replace('</ol></p>', '</ol>', $html);
        return $html;
    }
}
