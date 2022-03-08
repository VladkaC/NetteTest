<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
    $this->database = $database;
    }

    // spouští se ihned na začátku životního cyklu presenteru.
    // Ta přesměruje nepřihlášené uživatele na formulář s přihlášením.
    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Log:in');
        }
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titulek:')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
            ->setHtmlAttribute('class', 'form-control ')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    public function postFormSucceeded(array $data): void
    {
        //když existuje článek s postId ?
        $postId = $this->getParameter('postId');

        // získá data z formuláře, vloží je do databáze, vytvoří zprávu pro uživatele o úspěšném uložení příspěvku a
        // přesměruje na stránku s novým příspěvkem, takže hned uvidíme, jak vypadá ve větvi else
        //if edituje existující článek
        if ($postId) {
            $post = $this->database
                ->table('posts')
                ->get($postId);
            $post->update($data);

        } else {
            $post = $this->database
                ->table('posts')
                ->insert($data);
        }

        $this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
        $this->redirect('Post:show', $post->id);
    }

    //zjišťuje jestli je stránka
    public function renderEdit(int $postId): void
    {
        $post = $this->database
            ->table('posts')
            ->get($postId);

        if (!$post) {
            $this->error('Post not found');
        }

        $this->getComponent('postForm')
            ->setDefaults($post->toArray());
    }


}