<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model;
use App\Model\DuplicateNameException;
use App\Model\UserManager;

/**
 * Sign-up form factory.
 */
final class SignUpFormFactory
{
    use Nette\SmartObject;

    private const PASSWORD_MIN_LENGTH = 8;

    /**
     * Constructor with dependency injection of form factory and user management services.
     * @param FormFactory $factory
     * @param Model\UserManager $userManager
     */
    public function __construct(private FormFactory $factory, private UserManager $userManager)
    {
    }

    /**
     * Creates a sign-up form.
     * @param callable $onSuccess Is executed upon successful registration of a new user.
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();
        $form->addText('username', 'Username:')
            ->setRequired('Please create a username.');
        $form->addEmail('email', 'Email:')
            ->setRequired('Please enter your email.');
        $form->addPassword('password', 'Create a password.')
            ->setOption('description', sprintf('at least %d characters long', self::PASSWORD_MIN_LENGTH))
            ->setRequired('Please create a password.')
            ->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);
        $form->addSubmit('send', 'Sign up');
        $form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
            try {
                $this->userManager->add($data->username, $data->email, $data->password);
            } catch (DuplicateNameException $ex) {
                $form['username']->addError('Username is already in use.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}