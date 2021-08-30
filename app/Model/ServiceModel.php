<?php

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class ServiceModel extends BaseModel
{
	protected string $table = 'services';

	public function getPublicServices(): ?Selection
	{
		return $this->getTable()->where('public', 1)->order('id ASC');
	}

	public function getService($slug): ?ActiveRow
	{
		return $this->getTable()->where('slug', $slug)->fetch();
	}
}
