<?php
namespace App\Presenters;

use App\Model\LogUser;
use Nette;

final class LogUserPresenter extends Nette\Application\UI\Presenter
{
    private LogUser $logUser;

    public function __construct(LogUser $logUser)
    {
        $this->logUser = $logUser;
    }

    public function renderAll() :void
    {
        $this->template->posts = $this->logUser->getAllLogUser();
    }
}