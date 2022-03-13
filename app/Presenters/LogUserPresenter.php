<?php
namespace App\Presenters;

use App\Model\DeleteUser;
use App\Model\LogUser;
use Nette;
use Nette\Application\UI\Form;

final class LogUserPresenter extends Nette\Application\UI\Presenter
{
    private LogUser $logUser;
    private DeleteUser $deleteUser;

    public function __construct(LogUser $logUser, DeleteUser $deleteUser)
    {
        $this->logUser = $logUser;
        $this->deleteUser = $deleteUser;
    }

    public function renderAll() :void
    {
        $this->template->posts = $this->logUser->getAllLogUser();
    }

}