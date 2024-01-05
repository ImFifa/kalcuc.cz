<?php declare(strict_types = 1);

namespace App\FrontModule\Presenter;

use Nette;
use function assert;
use function is_file;

class Error4xxPresenter extends BasePresenter
{

	public function startup(): void
	{
		$request = $this->getRequest();

		parent::startup();

		assert($request instanceof Nette\Application\Request);
		if (!$request->isMethod(Nette\Application\Request::FORWARD)) {
			$this->error();
		}
	}

	public function renderDefault(Nette\Application\BadRequestException $exception): void
	{
		assert($this->template instanceof Nette\Application\UI\Template);
		$file = __DIR__ . '/../Template/Error/' . $exception->getCode() . '.latte';
		$this->template->setFile(is_file($file) ? $file : __DIR__ . '/../Template/Error/4xx.latte');
		$this->template->message = $exception->getMessage();
	}

}
