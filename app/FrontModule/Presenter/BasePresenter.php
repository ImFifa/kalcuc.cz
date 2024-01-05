<?php declare(strict_types = 1);

namespace App\FrontModule\Presenter;

use K2D\Box\Component\BoxComponent\BoxComponent;
use K2D\Box\Component\BoxComponent\BoxComponentFactory;
use K2D\Core\Presenter\FrontBasePresenter;
use Nette\Application\Helpers;
use Nette\HtmlStringable;
use stdClass;

abstract class BasePresenter extends FrontBasePresenter
{

	/** @inject */
	public BoxComponentFactory $boxFactory;

	/**
	 * @param HtmlStringable|stdClass|string $message
	 */
	public function flashMessage($message, string $type = 'success'): stdClass
	{
		return parent::flashMessage($message, $type);
	}

	public function formatTemplateFiles(): array
	{
		[, $presenter] = Helpers::splitName($this->getName());

		return [
			__DIR__ . "/../Template/$presenter/" . $this->getView() . ".latte"
		];
	}

	protected function createComponentBox(): BoxComponent
	{
		return $this->boxFactory->create();
	}

}
