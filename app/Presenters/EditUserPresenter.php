<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class EditUserPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
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

    public function editUserDetailFormSucceeded(array $data): void
    {
        //když existuje člověk s id?
        $id = $this->getParameter('id');

        //edituje uživatele
        if ($id) {
            $post = $this->database
                ->table('users')
                ->get($id);
            $post->update($data);
        }

        $this->flashMessage('Změna proběhla úspěšně.', 'success');
        $this->redirect('LogUser:all');
    }
}