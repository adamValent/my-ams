<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Forms\FormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    protected FormFactory $formFactory;

    /**
     * Performs dependency injection of form factory.
     * @param FormFactory $formFactory
     * @return void
     */
    public final function injectFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Checks access rights before every action in any presenter.
     * @return void
     * @throws AbortException
     */
    protected function startup()
    {
        parent::startup();
        if (!$this->getUser()->isAllowed($this->getName(), $this->getAction())) {
            $this->flashMessage('You are not logged in or you do not have sufficient access rights.');
            $this->redirect(':Core:Administration:login');
        }
    }
}