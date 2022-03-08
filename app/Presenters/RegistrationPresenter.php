<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\user;
use App\Model\Registration;
Use Nette\Security\Authenticator;

final class RegistrationPresenter extends Nette\Application\UI\Presenter
{
    private Registration $registration;
    /** @var Passwords */
    private $passwords;
    private $user;

    public function __construct(Passwords $passwords , Registration $registration, Nette\Security\User $user)
    {
        $this->registration = $registration;
        $this->passwords = $passwords;
        $this->user = $user;
    }

    protected function createComponentRegistrationInForm() :Form
    {
        $form = new Form;
        $form->addEmail('email', 'Email:')
            ->setRequired('Zadejte prosím emali:');

        $form->addText('name', 'Jméno:')
            ->setRequired('Zadejte prosím jméno');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Zadejte prosím heslo')
            ->addRule($form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků', 8);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
            ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu:')
            ->addRule($form::EQUAL, 'Hesla se neshodují', $form['password']);

        $form->addText('y', 'Zadejte aktuální rok')->setOmitted()->setRequired()
            ->addRule(Form::EQUAL, 'Chybně vyplněný antispam.', date("Y"));

        $form->addSubmit('send', 'Registrovat');

            $form->onSuccess[] = [$this, 'RegistrationInFormSucceeded'];
            return $form;


    }

   public function RegistrationInFormSucceeded(Form $form, \stdClass $value): void
    {

        try {
            $this->registration->loginFormSubmitted($value->password, $value->name, $value->email);

            $this->flashMessage('Registrace proběhla úspěšně.');
            $this->redirect('Homepage:');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $form['name']->addError('Uživatelské jméno je již obsazeno.');
            $form['email']->addError('Email se již používá.');

        }



    }
}