<?php
namespace App\Model;

use Nette;
use Nette\Security\Passwords;

final class Registration
{
    use Nette\SmartObject;

    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }


    public function loginFormSubmitted($pass, $name, $email)
    {
        $password = $pass;
        $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
        $res = $passwords->hash($password); // Zahashuje heslo

        $this->database->table('users')->insert([
            'email'=> $email,
            'username' => $name,
            'password' => $res,
            'role' => 'registred'
        ]);
    }


}