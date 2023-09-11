<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

/**
 * Blank form factory.
 */
final class FormFactory
{
    use Nette\SmartObject;

    /**
     * Creates a blank form for further modification.
     * @return Form
     */
    public function create(): Form
    {
        return new Form;
    }
}