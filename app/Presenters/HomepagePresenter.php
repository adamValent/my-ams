<?php

declare(strict_types=1);

namespace App\Presenters;

/**
 * Homepage presenter.
 */
final class HomepagePresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->anyVariable = 'any value';
    }
}