<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\Mailer;
use Nette\Mail\SendException;
use Nette\Utils\ArrayHash;

/**
 * Contact form presenter.
 * @package App\CoreModule\Presenters
 */
class ContactPresenter extends BasePresenter
{
    /**
     * Constructor with contact email setting and dependency injection of mail service.
     * @param string $contactEmail Contact email.
     * @param Mailer $mailer Mail service.
     */
    public function __construct(private string $contactEmail, private Mailer $mailer)
    {
        parent::__construct();
    }

    /**
     * Creates contact form.
     * @return Form Contact form.
     */
    protected function createComponentContactForm(): Form
    {
        $form = new Form;
        $form->getElementPrototype()->setAttribute('novalidate', true);
        $form->addEmail('email', 'Your e-mail address')->setRequired();
        $form->addText('y', 'Enter actual year')->setOmitted()->setRequired()
            ->addRule(Form::Equal, 'Wrong antispam.', date("Y"));
        $form->addTextArea('message', 'Message')->setRequired()
            ->addRule(Form::MIN_LENGTH, 'Message must be at least %d characters long.', 10);
        $form->addSubmit('send', 'Send');
        $form->onSuccess[] = $this->contactFormSucceeded(...);
        return $form;
    }

    /**
     * Executes upon successful submit of contact form. Sends an email.
     * @param array|ArrayHash $data Data passed via submitted contact form.
     * @return void
     * @throws AbortException
     */
    private function contactFormSucceeded(array|ArrayHash $data): void
    {
        try {
            $mail = new Message;
            $mail->setFrom($data->email)
                ->addTo($this->contactEmail)
                ->setSubject('Email from web')
                ->setBody($data->message);
            $this->mailer->send($mail);
            $this->flashMessage('Email was sent successfully.');
            $this->redirect('this');
        } catch (SendException $ex) {
            $this->flashMessage('Unable to send email.');
        }
    }
}