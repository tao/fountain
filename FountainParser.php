<?php

namespace App\Fountain;

use App\Fountain\Elements\Action;
use App\Fountain\Elements\BlankLine;
use App\Fountain\Elements\NewLine;
use App\Fountain\Elements\CenteredText;
use App\Fountain\Elements\Character;
use App\Fountain\Elements\Boneyard;
use App\Fountain\Elements\Dialogue;
use App\Fountain\Elements\DualDialogue;
use App\Fountain\Elements\Lyrics;
use App\Fountain\Elements\Notes;
use App\Fountain\Elements\PageBreak;
use App\Fountain\Elements\Parenthetical;
use App\Fountain\Elements\SceneHeading;
use App\Fountain\Elements\SectionHeading;
use App\Fountain\Elements\Synopsis;
use App\Fountain\Elements\Transition;

use App\Fountain\FountainElementCollection;
use App\Fountain\FountainType as Type;

/**
 * FountainParser
 * Based off the FastFountainParser.m
 *
 * https://github.com/alexking/Fountain-PHP
 *
 * @author Alex King (PHP port)
 * @author Nima Yousefi & John August (original Objective-C version)
 */
class FountainParser
{

    /**
     * Element Collection
     * @var FountainElementCollection
     */
    protected $_elements;

    /**
     * Parse a string into a collection of elements
     *
     * @param  string  $contents  Fountain formatted text
     * @param  bool  $strict
     * @return \App\Fountain\FountainElementCollection
     */
    public function parse($contents, $strict = false)
    {
        //-----------------------------------------------------
        // Prepare the file contents
        //-----------------------------------------------------

        // trim newlines from the document
        $contents = trim($contents);

        // convert \r\n or \r style newlines to \n
        $contents = preg_replace("/\r\n|\r/", "\n", $contents);

        // add two line breaks to the end of the page (ref FastFountainParser.m:53)
        if ($strict) {
            $contents .= "\n\n";
        }

        // add an end element to the document
        $contents .= "\n\n<<<END";

        // keep track of the first line for processing certain elements
        $first_line = true;
        $last_line = false;

        // keep track of preceding newlines
        $newlines_before = 0;
        $newline = false;

        // keep track of whether we are inside a comment block, and what its text is
        $comment_block = false;
        $comment_text = '';

        // keep track of whether we are inside a dialog block
        $dialog_block = false;

        //-----------------------------------------------------
        // Process each line separately
        //-----------------------------------------------------

        // split the contents into lines
        $lines = explode("\n", $contents);

        // process each line
        foreach ($lines as $line_number => $line) {

            // reset the newline count if necessary
            if (!$newline) {
                $newlines_before = 0;
            }

            // keep track of the first line
            if ($line_number > 0) {
                $first_line = false;
            }

            // determine if this is the last line
            if ($line === '<<<END') {
                $last_line = true;
                $line = '';
            }

            /**
             * -----------------------------------------------------
             * Blank Lines
             * -----------------------------------------------------
             */

            if ((new BlankLine())->match($line)) {
                // check if the previous element was dialogue
                $last_element = $this->last_element();
                if ($last_element && $last_element->type == Type::Dialogue) {
                    // the previous element was dialogue, so we can combine the text.
                    // two line breaks (\n\n) are necessary for markdown to split
                    // the dialogue elements into separate paragraph tags.
                    $last_element->text .= "\n\n" . $line;
                }

                continue;
            }

            // assert if this is a blank line
            $assertBlankLine = (new NewLine())->match($line) && !$comment_block;
            if ($last_line) $assertBlankLine = false;

            // check for a blank line (line is empty, or has whitespace characters)
            if ($assertBlankLine) {
                $dialog_block = false;      // blank lines end dialog blocks
                $newlines_before++;         // increment newline count
                $newline = true;
                continue;                   // no further processing needed
            } else {
                $newline = false;           // note that this isn't a newline
            }

            /**
             * -----------------------------------------------------
             * Synopses
             * -----------------------------------------------------
             * If there aren't any preceding newlines, and there's a "="
             */

            // check if there is a blank line before this element
            // or if this element is on the first line
            $assertNewline = ($newlines_before > 0 || $first_line);
            $assertSynopsis = ($assertNewline && (new Synopsis())->match($line));

            if ($assertSynopsis) {
                $synopsis = (new Synopsis())->sanitize($line);

                $upgradeSynopsis = ($first_line);

                // add a synopsis element
                $this->add_element(Type::Synopsis, $synopsis, array(
                    'first' => $upgradeSynopsis
                ));

                continue;
            }

            /**
             * -----------------------------------------------------
             * Boneyard Blocks
             * -----------------------------------------------------
             */

            // check whether a comment starts or ends on this line
            $boneyard = (new Boneyard())->match($line);
            $assertComments = ($boneyard || $comment_block);

            // if this is the start, middle, or end of a comment block
            if ($assertComments) {
                // if the comment ends on this line
                if ($boneyard && $comment_block) {
                    $comment_text = (new Boneyard())->sanitize($line);
                    $this->add_element(Type::Boneyard, $comment_text);
                    $comment_block = false;
                    $comment_text = ''; // reset the text
                }

                // if it starts on this line
                if ($boneyard && !$comment_block) $comment_block = true;

                // if the comment continues on this line
                if (!$boneyard && $comment_block) $comment_text .= "$line\n";

                // no further processing of this line is needed
                continue;
            }

            /**
             * -----------------------------------------------------
             * Page Breaks
             * -----------------------------------------------------
             */

            if ((new PageBreak())->match($line)) {
                // add a page break element
                $this->add_element(Type::PageBreak, $line);
                continue;
            }

            /**
             * -----------------------------------------------------
             * Lyrics
             * -----------------------------------------------------
             * If there is a preceding newline, and there's a "~"
             * Additional lyrical lines will be appended later.
             */

            $assertLyrics = ($newlines_before && (new Lyrics())->match($line));

            if ($assertLyrics) {
                $lyric = (new Lyrics())->sanitize($line);
                $this->add_element(Type::Lyrics, $lyric);
                continue;
            }

            /**
             * -----------------------------------------------------
             * Action
             * -----------------------------------------------------
             * If there aren't any preceding newlines, and there's a "!"
             * Additional action lines will be appended later.
             */

            if ((new Action())->match($line)) {
                $action = (new Action())->sanitize($line);
                $this->add_element(Type::Action, $action);
                continue;
            }

            /**
             * -----------------------------------------------------
             * Notes
             * -----------------------------------------------------
             */

            if ($newlines_before && (new Notes())->match($line)) {
                $text = (new Notes())->sanitize($line);
                $this->add_element(Type::Notes, $text);
                $newline = true; // force next element to start in a new element/thread
                continue;
            }

            /**
             * -----------------------------------------------------
             * Section Headings
             * -----------------------------------------------------
             * check if this line starts with a #
             *
             * WARNING: Modified to allow other elements to appear after a heading
             *                    without having a blank line between them
             */

            $assertSection = (new SectionHeading())->match($line);
            if ($strict) $assertSection = ($newlines_before && $assertSection);

            if ($assertSection) {
                // add a section heading
                $this->add_element(Type::SectionHeading, $line);
                if (!$strict) $newline = true; // force next element to start in a new element/thread
                continue;
            }

            /**
             * -----------------------------------------------------
             * Scene Headings
             * -----------------------------------------------------
             * WARNING: Modified to allow only INT. or EXT. Scene headings
             */

            if ((new SceneHeading())->match($line, $strict)) {
                $text = (new SceneHeading())->sanitize($line);
                $this->add_element(Type::SceneHeading, $text);
                continue;
            }

            /**
             * -----------------------------------------------------
             * Centered
             * -----------------------------------------------------
             * Check whether the line starts with > and ends with <
             */

            if ((new CenteredText())->match($line)) {
                $text = (new CenteredText())->sanitize($line);

                // check if the previous element was centered
                $last_element = $this->last_element();
                if (
                    (!$newlines_before) &&
                    ($last_element && $last_element->type == Type::CenteredText)
                ) {
                    // the previous element was centered, so we can combine the text.
                    $last_element->text .= "\n".$text;
                } else {
                    $this->add_element(Type::CenteredText, $text);
                }

                continue;
            }

            /**
             * -----------------------------------------------------
             * Transitions
             * -----------------------------------------------------
             * This element will be parsed but not included in the output.
             */

            if ((new Transition())->match($line)) {
                $text = (new Transition())->sanitize($line);
                $this->add_element(Type::Transition, $text);
                continue;
            }

            /**
             * -----------------------------------------------------
             * Character
             * -----------------------------------------------------
             * check if there is a newline preceding (or first line)
             * and consists of entirely uppercase characters
             *
             */

            // check if there is a blank line before this element
            // or if this element is on the first line
            $assertNewline = ($newlines_before || $first_line);

            // assert a character element
            $assertCharacter = $assertNewline && (new Character())->match($line);

            if ($assertCharacter) {
                // make sure the next line isn't blank or non-existent
                if (isset($lines[$line_number + 1]) && $lines[$line_number + 1] != "") {
                    // this is a character, check if it's dual dialog
                    $dual_dialog = false;

                    if ((new DualDialogue())->match($line)) {
                        // it is dual dialog,
                        $dual_dialog = true;

                        // check for a previous character - grab it by reference if it exists
                        if ($previous_character = &$this->elements()->find_last_element_of_type(Type::Character)) {
                            // set it to dual dialog
                            $previous_character->dual_dialog = true;
                        }
                    }

                    // add a character element
                    $extras = array('dual_dialog' => $dual_dialog);
                    $character = (new Character())->sanitize($line);
                    $this->add_element(Type::Character, $character, $extras);

                    // Check if the Character contains inline parenthesis
                    if (preg_match("/\(.*\)/", $line, $matches)) {
                        $this->add_element(Type::Parenthetical, $matches[0]);
                    }

                    // note that we're within a dialog block
                    $dialog_block = true;
                    continue;
                }
            }

            /**
             * -----------------------------------------------------
             * Dialogue (and Parenthetical)
             * -----------------------------------------------------
             */

            // assert if this is a parenthetical element
            $assertParenthetical = !$newlines_before && (new Parenthetical())->match($line);

            // check if we're inside a dialog block
            if ($dialog_block) {

                // handle parenthesis elements
                if ($assertParenthetical) {
                    // add a parenthetical element
                    $this->add_element(Type::Parenthetical, $line);
                    // force new elements to start in a new parent element/thread
                    $newline = true;
                }

                // handle dialogue elements
                if (!$assertParenthetical) {
                    // check if the previous element was dialogue
                    $last_element = $this->last_element();

                    if ($last_element && $last_element->type == Type::Dialogue) {
                        // the previous element was dialogue, so we can combine the text.
                        // two line breaks (\n\n) are necessary for markdown to split
                        // the dialogue elements into separate paragraph tags.
                        $last_element->text .= "\n\n".$line;
                    } else {
                        // return dialogue as the default element
                        // WARNING: previously action was returned as default
                        $this->add_element(Type::Dialogue, $line);
                    }
                }

                continue;
            }

            // if there were no newline before, and this isn't our first element
            if (!$newlines_before) {

                /**
                 * If there are no newlines before
                 * determine if the current element matches the previous element,
                 * then append onto the previous instead of creating a new one.
                 * This allows us to create blocks of text under one parent tag
                 */

                // find the previous element ('&' as reference)
                $last_element = $this->last_element();
                if (!$last_element) continue;

                switch ($last_element->type) {
                    case Type::Lyrics:
                    {
                        // lyrics should not be separated into paragraphs
                        // add this line to the previous element with a single line break
                        $lyric = (new Lyrics())->sanitize($line);
                        $last_element->text .= PHP_EOL.$lyric;
                        break;
                    }
                    case Type::CenteredText:
                    {
                        $text = (new CenteredText())->sanitize($line);
                        $last_element->text .= PHP_EOL.$text;
                        break;
                    }
                    case Type::Synopsis:
                    {
                        $text = (new Synopsis())->sanitize($line);
                        $last_element->text .= PHP_EOL.PHP_EOL.$text;
                        break;
                    }
                    case Type::Dialogue:
                    {
                        $last_element->text .= PHP_EOL.$line;
                        break;
                    }
                    default:
                    {
                        // add this line to the previous element
                        // using a double newline for a line break in markdown
                        $last_element->text .= PHP_EOL.PHP_EOL.$line;
                    }
                }

                 continue;
            }

            /**
             * If there are newlines previously
             */
            if ($newlines_before && $line !== "") {
                // check if this starts with a (
                // WARNING: modification to allow parenthesis to exist on its own
                if ((new Parenthetical())->match($line)) {
                    // add a parenthetical element
                    $this->add_element(Type::Parenthetical, $line);
                    // force new elements to start in a new parent element/thread
                    $newline = true;
                } else {
                    // return dialogue as the default element
                    // WARNING: previously action was returned as default
                    $this->add_element(Type::Action, $line);
                }

                 continue;
            }
        }

        return $this->elements();
    }

    /**
     * Elements
     */
    public function elements()
    {
        if (!$this->_elements) {
            $this->_elements = new FountainElementCollection;
        }

        return $this->_elements;
    }

    /**
     * Add an Element to the collection
     * @param  string  $type  Character, Dialog, etc.
     * @param  string  $text  Text for the element
     * @param  array  $extras  Additional properties
     */
    public function add_element($type, $text, $extras = array())
    {
        $this->elements()->create_and_add_element($type, $text, $extras);
    }

    /**
     * Detect previous element collection type and get last element
     *
     * @return false|mixed
     */
    private function last_element()
    {
        $last_element = &$this->elements()->last_element();
        return $last_element;
    }
}
