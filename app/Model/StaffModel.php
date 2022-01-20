<?php declare(strict_types = 1);

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class StaffModel extends BaseModel
{

	protected string $table = 'staff';

	public function getStaffById(int $id): ActiveRow
	{
		return $this->getTable()->where('public', 1)->where('id', $id)->fetch();
	}

	public function getStaff(): array
	{
		return $this->getTable()->where('public', 1)->order('id ASC')->fetchAll();
	}

	public function getStaffByClass(int $class_id): array
	{
		return $this->getTable()->where('public', 1)->where('class_id', $class_id)->order('id ASC')->fetchAll();
	}

}
