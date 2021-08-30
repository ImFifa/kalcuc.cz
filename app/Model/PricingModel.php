<?php

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class PricingModel extends BaseModel
{
	protected string $table = 'pricing';

	public function getPricing(): ?Selection
	{
		return $this->getTable()->order('position ASC')->order('id ASC');
	}
}
