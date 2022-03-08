<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $postId): void
	{
		$post = $this->database
			->table('posts')
			->get($postId);
		if (!$post) {
			$this->error('Stránka nebyla nalezena');
		}

		$this->template->post = $post;
		$this->template->comments = $post->related('comments')->order('created_at');
	}

	protected function createComponentCommentForm(): Form
	{	
	$form = new Form; // means Nette\Application\UI\Form

	$form->addHidden('name', 'Jméno:')
		->setRequired()
        ->setDefaultValue($this->getUser()->getIdentity()->name);

	$form->addTextArea('content')
        ->setHtmlAttribute('class', ' ')
		->setRequired();


	$form->addSubmit('send', 'Publikovat komentář');

    //„po úspěšném odeslání formuláře zavolej metodu commentFormSucceeded ze současného presenteru“
	$form->onSuccess[] = [$this, 'commentFormSucceeded'];

	return $form;
	}
    //$data data z formuláře
	public function commentFormSucceeded(\stdClass $data): void
	{
	$postId = $this->getParameter('postId');

	$this->database->table('comments')->insert([
		'post_id' => $postId,
		'name' => $data->name,
		'content' => $data->content,
	]);

	$this->flashMessage('Děkuji za komentář', 'success');
	$this->redirect('this');
	}

}
