<?php
/*namespace App\Model;

use Nette;
use Nette\Security\Authorizator;

class MyAuthorizator implements Nette\Security\Authorizator
{
    public function isAllowed($role, $resource, $operation): bool
    {
        $acl = new Nette\Security\Permission;

        $acl->addRole('guest');
        $acl->addRole('registered', 'guest'); // 'registered' dědí od 'guest'
        $acl->addRole('admin', 'registered'); // a od něj dědí 'admin'

        $acl->addResource('comment');
        $acl->allow('guest', 'comment', 'view');
        $acl->allow('registered', 'comment', 'add');
        $acl->allow('admin', $acl::ALL, ['view', 'edit', 'add']);

    }
}*/
