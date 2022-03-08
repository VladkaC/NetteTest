<?php
namespace App\Model;

use Nette;
use Nette\Security\Passwords;

final class Edit
{
    use Nette\SmartObject;

    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }


    public function editPostForm($data,$postId)
    {

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
        $this->redirect('Post:show', $post->id);
    }

    //editace příspěvku
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