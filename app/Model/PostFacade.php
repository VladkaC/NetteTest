<?php
namespace App\Model;

use Nette;

final class PostFacade
{
    use Nette\SmartObject;

    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }

    //vybere články od nejnovějšího
    //musíme přidat do config services.neon - App\Model\PostFacade
    public function getPublicArticles()
    {
        return $this->database
            ->table('posts')
            ->where('created_at < ', new \DateTime)
            ->order('created_at DESC');
    }
}
