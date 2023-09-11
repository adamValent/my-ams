<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use App\Presenters\BasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;

/**
 * Administration section rendering presenter.
 */
class AdministrationPresenter extends BasePresenter
{
    public function __construct(private SignInFormFactory $signInFactory,
                                private SignUpFormFactory $signUpFactory)
    {
        parent::__construct();
    }

    /**
     * Redirects user to the administration when user logs in.
     * @return void
     * @throws AbortException
     */
    public function actionLogin(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Administration:');
        }
    }

    /**
     * Logs out the user and redirects him to the login page.
     * @return void
     * @throws AbortException
     */
    public function actionLogout(): void
    {
        $this->user->logout();
        $this->redirect('login');
    }

    /**
     * Passes name of the user to the administration page.
     * @return void
     */
    public function renderDefault(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->template->username = $this->user->identity->username;
        }
    }

    /**
     * Creates a sign-in form.
     * @return Form
     */
    protected function createComponentLoginForm(): Form
    {
        return $this->signInFactory->create(function (): void {
            $this->flashMessage('You have been logged in successfully.');
            $this->redirect('Administration:');
        });
    }

    /**
     * Creates a sign-up form.
     * @return Form
     * @throws AbortException
     */
    protected function createComponentRegisterForm(): Form
    {
        return $this->signUpFactory->create(function (): void {
            $this->flashMessage('You have been registered successfully.');
            $this->redirect('Administration:');
        });
    }
}