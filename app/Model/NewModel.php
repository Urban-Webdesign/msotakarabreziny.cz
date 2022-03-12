<?php declare(strict_types = 1);

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class NewModel extends BaseModel
{

	protected string $table = 'articles';

	public function getNew(string $slug, string $lang): ?ActiveRow
	{
		return $this->getTable()->where('public', 1)->where('lang', $lang)->where('slug', $slug)->fetch();
	}

    public function getPublicNews(string $lang): Selection
    {
        return $this->getTable()->where('public', 1)->where('lang', $lang)->order('created DESC')->order('id DESC');
    }

    public function getPublicNewsByCategory(int $category_id): ?Selection
    {
        return $this->getTable()->where('public', 1)->whereOr(["category_id" => $category_id, "category_id2" => $category_id])->order('created DESC')->order('id DESC');
    }

    public function getNewsByCategoryCount(int $category_id): int
    {
        return $this->getTable()->where('public', 1)->whereOr(["category_id" => $category_id, "category_id2" => $category_id])->count();
    }

}
