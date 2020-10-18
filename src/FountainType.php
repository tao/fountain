<?php

namespace Fountain;

use Fountain\Elements\Action;
use Fountain\Elements\NewLine;
use Fountain\Elements\BlankLine;
use Fountain\Elements\TextCenter;
use Fountain\Elements\Character;
use Fountain\Elements\Boneyard;
use Fountain\Elements\Dialogue;
use Fountain\Elements\Lyrics;
use Fountain\Elements\Notes;
use Fountain\Elements\Pagebreak;
use Fountain\Elements\Parenthetical;
use Fountain\Elements\SceneHeading;
use Fountain\Elements\SectionHeading;
use Fountain\Elements\Synopsis;
use Fountain\Elements\Transition;

class FountainType
{
    /**
     * @dataProvider provideRoles
     */
    public function provideRoles()
    {
        return [
            (new Action()),
            (new NewLine()),
            (new BlankLine()),
            (new TextCenter()),
            (new Character()),
            (new Boneyard()),
            (new Dialogue()),
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
