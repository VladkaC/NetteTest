<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\SimpleIdentity;
use Nette\Security\user;

class LogPresenter extends Nette\Application\UI\Presenter
{

    private User $user;

    public function __construct(Nette\Security\User $user)
    {
        $this->user = $user;
    }
    public function renderIn()
    {
        //dumpe($this->user);
       $this->template->user = $this->user;
    }

    protected function createComponentLogInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Prosím vyplňte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte své heslo.');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'LogInFormSucceeded'];
        return $form;
    }

    public function LogInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }

}