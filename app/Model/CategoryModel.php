<?php declare(strict_types = 1);

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;

class CategoryModel extends BaseModel
{

	protected string $table = 'categories';

	/**
	 * @return array<ActiveRow>
	 */
	public function getNewsByCategory(string $category): array
	{
		return $this->getItemBy($category, 'name')->related('articles.category_id')->fetchAll();
	}

}
