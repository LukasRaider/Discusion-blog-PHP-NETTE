<?php
namespace App\Presenters;


use \Nette\Application\UI\Presenter;
use App\Model\DiscussionManager;
use \Nette\Application\UI\Form;

class DiscussionPresenter extends \Nette\Application\UI\Presenter{
	/**
	 * @var \App\Model\DiscussionManager
	 */
	private $discussionManager;
	
	function __construct(DiscussionManager $discussionManager){
		$this->discussionManager = $discussionManager;
		parent::__construct();
	}
    public function renderDefault(){		
		$this->template->discussionItems = $this->discussionManager->getDisscussionItems();
		$this->template->title = 'Seznam příspěvků';
	}

    public function createComponentDiscussionForm(){
        $form = new Form();
        $form->addText('nick', 'Přezdívka:');
        $form->addText('email', 'Email:');
        $form->addTextArea('data', 'Příspěvek');
        $form->addSubmit('insert', 'Vložit');
        $form->onSuccess[] = [$this, 'discussionItemInsert'];
        return $form;
}

public function discussionItemInsert(Form $form, $values){
    $values = $form->getValues();
    $this->discussionManager->saveDiscussionItems($values['nick'], $values['email'], $values['data']);
    $this->flashMessage('Příspěvek vložen.');        
}

public function renderDetail($id){
    $this->template->discussionItem = $this->discussionManager->getDisscussionItem($id);
    $this->template->title = 'Příspěvek';        
}

public function actionAddPositive($id){        
    $this->discussionManager->addPositive($id);
    $this->flashMessage('Pozitivní hodnocení vloženo.');
    $this->redirect('Discussion:');
}

public function actionAddNegative($id){        
    $this->discussionManager->addNegative($id);
    $this->flashMessage('Negativní hodnocení vloženo.');
    $this->redirect('Discussion:');       
}

}

?>