<?php

namespace App\Fountain;

use App\Fountain\Elements\Action;
use App\Fountain\Elements\NewLine;
use App\Fountain\Elements\BlankLine;
use App\Fountain\Elements\Blockquote;
use App\Fountain\Elements\CenteredText;
use App\Fountain\Elements\Character;
use App\Fountain\Elements\Code;
use App\Fountain\Elements\Boneyard;
use App\Fountain\Elements\Dialogue;
use App\Fountain\Elements\DualDialogue;
use App\Fountain\Elements\Footnote;
use App\Fountain\Elements\Lyrics;
use App\Fountain\Elements\Notes;
use App\Fountain\Elements\Pagebreak;
use App\Fountain\Elements\Parenthetical;
use App\Fountain\Elements\SceneHeading;
use App\Fountain\Elements\SectionHeading;
use App\Fountain\Elements\Synopsis;
use App\Fountain\Elements\Transition;

class FountainType
{
    public const Action = 'Action';
    public const Boneyard = 'Code';
    public const NewLine = 'NewLine';
    public const BlankLine = 'BlankLine';
    public const Character = 'Character';
    public const Code = 'Code';
    public const Dialogue = 'Dialogue';
    public const DualDialogue = 'DualDialogue';
    public const Parenthetical = 'Parenthetical';
    public const Lyrics = 'Lyrics';
    public const Transition = 'Transition';
    public const CenteredText = 'CenteredText';
    public const Emphasis = 'Emphasis';
    // public const TitlePage = 'TitlePage';
    public const PageBreak = 'PageBreak';
    // public const Punctuation = 'Punctuation';
    // public const LineBreaks = 'LineBreaks';
    public const Notes = 'Notes';
    public const SceneHeading = 'SceneHeading';
    public const SectionHeading = 'SectionHeading';
    public const Synopsis = 'Synopsis';

    /**
     * @dataProvider provideRoles
     */
    public function provideRoles()
    {
        return [
            (new Action()),
            (new NewLine()),
            (new BlankLine()),
            (new CenteredText()),
            (new Character()),
            (new Code()),
            (new Boneyard()),
            (new Dialogue()),
            (new DualDialogue()),
            (new Lyrics()),
            (new Notes()),
            (new PageBreak()),
            (new Parenthetical()),
            (new SceneHeading()),
            (new SectionHeading()),
            (new Synopsis()),
            (new Transition()),
        ];
    }

}
