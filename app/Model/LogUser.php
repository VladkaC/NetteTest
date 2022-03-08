<?php
namespace App\Model;

use Nette;

final class LogUser
{
    use Nette\SmartObject;

    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }

    //výpis všech uživatelů
    //musíme přidat do config services.neon - App\Model\PostFacade
    public function getAllLogUser()
    {
        return $this->database
            ->table('users')
            ->order('username DESC');
    }
}