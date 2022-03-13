<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\DeleteUser;
use App\Model\Edit;

final class EditUserPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Explorer $database;
    private DeleteUser $deleteUser;
    private Edit $edit;

    public function __construct(Nette\Database\Explorer $database, DeleteUser $deleteUser, Edit $edit)
    {
        $this->database = $database;
        $this->deleteUser = $deleteUser;
        $this->edit = $edit;
    }

    public function renderDetail(int $id): void
    {
        $post = $this->database
            ->table('users')
            ->get($id);
        if (!$post) {
            $this->error('Stránka nebyla nalezena');
        }

        $this->getComponent('editUserDetailForm')
            ->setDefaults($post->toArray());
    }

    protected function createComponentEditUserDetailForm() :Form
    {
        $form = new Form;
        $form->addEmail('email', 'Email:')
            ->setRequired('Zadejte prosím emali:');

        $form->addText('username', 'Jméno:')
            ->setRequired('Zadejte prosím jméno');

        $form->addText('y', 'Zadejte aktuální rok')->setOmitted()->setRequired()
            ->addRule(Form::EQUAL, 'Chybně vyplněný antispam.', date("Y"));

        $form->addSubmit('send', 'Změnit');

        $form->onSuccess[] = [$this, 'editUserDetailFormSucceeded'];
        return $form;

    }

    //změna uživatele
    public function editUserDetailFormSucceeded(array $data): void
    {
        //když existuje člověk s id?
        $id = $this->getParameter('id');

        //edituje uživatele
        $this->edit->editUser($id, $data);

        $this->flashMessage('Změna proběhla úspěšně.', 'success');
        $this->redirect('LogUser:all');
    }

    //smazání uživatele
    protected function createComponentDeleteUserDetailForm() :Form
    {
        $form = new Form;
        $form->addSubmit('send', 'Smazat uživatele');

        $form->onSuccess[] = [$this, 'DeleteUserDetailFormSucceeded'];
        return $form;

    }

    public function DeleteUserDetailFormSucceeded(array $data): void
    {
        //když existuje člověk s id?
        $id = $this->getParameter('id');
        try {
            $this->deleteUser->deleteFormSubmitted($data, $id);

            $this->flashMessage('Smazání proběhlo úspěšně.');
            $this->redirect('Homepage:');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Něco se pokazilo.');
        }
    }
}