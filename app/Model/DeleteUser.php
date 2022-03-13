<?php
namespace App\Model;

use Nette;
use Nette\Security\Passwords;

final class DeleteUser
{
use Nette\SmartObject;

private Nette\Database\Explorer $database;

public function __construct(Nette\Database\Explorer $database)
{
$this->database = $database;
}


public function deleteFormSubmitted($data, $id)
{

        //edituje uživatele
        if ($id) {
            $post = $this->database
                ->table('users')
                ->get($id);
            $post->delete($data);
        }
}


}