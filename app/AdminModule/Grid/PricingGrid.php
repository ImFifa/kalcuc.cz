<?php declare(strict_types=1);

namespace App\AdminModule\Grid;

use App\Model\PricingModel;
use K2D\Core\AdminModule\Grid\BaseV2Grid;

use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Forms\Container;

class PricingGrid extends BaseV2Grid
{

	private PricingModel $pricingModel;

	public function __construct(PricingModel $pricingModel)
	{
		parent::__construct();
		$this->pricingModel = $pricingModel;
	}

	protected function build(): void
	{
		$this->model = $this->pricingModel;

		parent::build();

		$this->setDefaultOrderBy('name', true);
		$this->setFilterFactory([$this, 'gridFilterFactory']);

		$this->addColumn('name', 'Název služby');
		$this->addColumn('price', 'Cena')->setSortable();;
		$this->addColumn('updated', 'Poslední úprava')->setSortable();

		$this->addRowAction('edit', 'Upravit', static function (): void {});
		$this->addRowAction('delete', 'Smazat', static function (ActiveRow $record): void {
			$record->delete();
		})
			->setProtected(false)
			->setConfirmation('Opravdu chcete smazat položku z ceníku?');
	}

	public function gridFilterFactory(Container $c): void
	{
		$c->addText('name', 'Název služby')->setHtmlAttribute('placeholder', 'Filtrovat dle názvu služby');
	}
}
