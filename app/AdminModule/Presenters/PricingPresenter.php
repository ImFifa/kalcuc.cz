<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Grid\PricingGridFactory;
use App\AdminModule\Grid\PricingGrid;
use App\Model\PricingModel;
use K2D\Core\AdminModule\Presenter\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Strings;


/**
 * @property-read ActiveRow|null $pricing
 */
class PricingPresenter extends BasePresenter
{
	/** @inject */
	public PricingModel $pricingModel;

	/** @var PricingGridFactory @inject */
	public $pricingGridFactory;

	public function renderEdit(?int $id = null): void
	{
		$this->template->pricing = null;

		if ($id !== null && $this->pricing !== null) {
			$pricing = $this->pricing->toArray();

			$form = $this['editForm'];
			$form->setDefaults($pricing);

			$this->template->pricing = $this->pricing;
		}
	}

	public function createComponentEditForm(): Form
	{
		$form = new Form();

		$form->addText('name', 'Název služby:')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 120)
			->setRequired('Název služby je povinný');

		$form->addText('price', 'Cena:')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 40)
			->setRequired('Cena služby je povinná');

		$form->addSubmit('save', 'Uložit');

		$form->onSubmit[] = function (Form $form) {
			$values = $form->getValues(true);
			$pricing = $this->pricing;

			if ($pricing === null) {
				$pricing = $this->pricingModel->insert($values);
				$this->flashMessage('Ceníková položka vytvořena');
			} else {
				$pricing->update($values);
				$this->flashMessage('Ceníková položka upravena');
			}

			$this->redirect('this', ['id' => $pricing->id]);
		};

		return $form;
	}

	protected function createComponentPricingGrid(): PricingGrid
	{
		return $this->pricingGridFactory->create();
	}


	protected function getPricing(): ?ActiveRow
	{
		return $this->pricingModel->get($this->getParameter('id'));
	}
}
