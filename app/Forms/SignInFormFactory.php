<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

/**
 * Sign-in form factory.
 */
final class SignInFormFactory
{
    use Nette\SmartObject;

    /**
     * Constructor with dependency injection of a form factory and a user.
     * @param FormFactory $factory Form factory.
     * @param User $user App user.
     */
    public function __construct(private FormFactory $factory,
                                private User        $user)
    {
    }

    /**
     * Creates a sign-in form.
     * @param callable $onSuccess Is executed on successful sign-in of a user.
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();
        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username.');
        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');
        $form->addCheckbox('remember', 'Remember me');
        $form->addSubmit('send', 'Sign in');
        $form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
            try {
                $this->user->setExpiration($data->remember ? '14 days' : '20 minutes');
                $this->user->login($data->username, $data->password);
            } catch (Nette\Security\AuthenticationException $ex) {
                $form->addError('The username or password is incorrect.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}