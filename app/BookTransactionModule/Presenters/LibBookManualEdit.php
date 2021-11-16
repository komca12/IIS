<?php

declare(strict_types=1);

namespace App\BookTransactionModule\Presenters;

use Nette\Utils\DateTime;
use App\LoginModule\Model\DuplicateNameException;
use Nette;
use Nette\Application\UI\Form;
use App\BookTransactionModule\Model\BookTransactionModel;

final class LibBookManualEditPresenter extends \App\CoreModule\Presenters\LogedPresenter
{
	protected function startup(): void
	{
		parent::startup();
		$this->resorceAutorize('Knihovna');
	}

    private BookTransactionModel $BTM;

	public function __construct(BookTransactionModel $BTM)
	{
        $this->BTM=$BTM;
	}

	private $row;
	public function renderDefault(string $libName,string $titul): void
	{
		$this->row=$this->BTM->getRowPoskytuje( $libName, $titul);
		$this->template->poskytuje=$this->row;
	}

	protected function createComponentBookEditForm(): Form
	{
		$form = new Form;
		$form->addHidden('ID_knihovna');
		$form->addHidden('ID_titul');

		$form->addInteger('mnozstvi', 'Množství:')->addRule($form::MIN, 'Minimální množství je 0', 0);

		$form->setDefaults($this->row);	



		$form->addSubmit('submit', 'Edituj počet')->onClick[] = [$this, 'EditItem'];
		$form->addProtection();

		return $form;
	}

	public function EditItem(Form $form, \stdClass $values): void
	{
		$this->BTM->editBookN($values->ID_knihovna,$values->ID_titul,$values->mnozstvi);
		$this->redirect('LibBooks:',$values->ID_knihovna);
	}
}
