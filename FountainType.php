<?php

namespace App\Fountain;

use App\Fountain\Elements\Action;
use App\Fountain\Elements\NewLine;
use App\Fountain\Elements\BlankLine;
use App\Fountain\Elements\TextCenter;
use App\Fountain\Elements\Character;
use App\Fountain\Elements\Boneyard;
use App\Fountain\Elements\Dialogue;
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
