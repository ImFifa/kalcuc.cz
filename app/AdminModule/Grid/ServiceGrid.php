<?php declare(strict_types=1);

namespace App\AdminModule\Grid;

use App\Model\ServiceModel;
use K2D\Core\AdminModule\Grid\BaseV2Grid;

use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Forms\Container;

class ServiceGrid extends BaseV2Grid
{

	private ServiceModel $serviceModel;

	public function __construct(ServiceModel $serviceModel)
	{
		parent::__construct();
		$this->serviceModel = $serviceModel;
	}

	protected function build(): void
	{
		$this->model = $this->serviceModel;

		parent::build();

		$this->setDefaultOrderBy('name', true);
		$this->setFilterFactory([$this, 'gridFilterFactory']);

		$this->addColumn('name', 'Název');
		$this->addColumn('gallery', 'Připojená galerie');
		$this->addColumn('updated', 'Poslední úprava')->setSortable();
		$this->addColumn('public', 'Veřejná');

		$this->addRowAction('edit', 'Upravit', static function (): void {});
		$this->addRowAction('delete', 'Smazat', static function (ActiveRow $record): void {
			if ($record->profile) {
				unlink(WWW . '/upload/services/' . $record->id . '/' . $record->profile);
			}

			$record->delete();
		})
			->setProtected(false)
			->setConfirmation('Opravdu chcete smazat službu?');
	}

	public function gridFilterFactory(Container $c): void
	{
		$c->addText('surname', 'Příjmení')->setHtmlAttribute('placeholder', 'Filtrovat dle příjmení');
		$c->addSelect('sport')
			->setPrompt('Filtrovat dle hlavního sportu')
			->setItems([0 => 'Triatlon', 1 => 'Běh', 2 => 'Cyklistika']);
		$c->addSelect('public')
			->setPrompt('Zveřejněno')
			->setItems([0 => 'Ne', 1 => 'Ano']);
	}
}
