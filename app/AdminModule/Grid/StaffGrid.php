<?php declare(strict_types=1);

namespace App\AdminModule\Grid;

use App\Model\StaffModel;
use K2D\Core\AdminModule\Grid\BaseV2Grid;
use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Forms\Container;

class StaffGrid extends BaseV2Grid
{

	/** @var StaffModel */
	private $staffModel;

	public function __construct(StaffModel $staffModel)
	{
		parent::__construct();
		$this->staffModel = $staffModel;
	}

	protected function build(): void
	{
		$this->model = $this->staffModel;

		parent::build();

		$this->setFilterFactory([$this, 'gridFilterFactory']);

		$this->addColumn('name', 'Jméno');
		$this->addColumn('surname', 'Příjmení');
		$this->addColumn('class_id', 'Třída');
		$this->addColumn('description', 'Poznámka');
		$this->addColumn('public', 'Veřejný');

		$this->addRowAction('edit', 'Upravit', static function () {});
		$this->addRowAction('delete', 'Smazat', static function (ActiveRow $record) {
			$record->delete();
		})
			->setProtected(false)
			->setConfirmation('Opravdu chcete zaměstnance smazat?');
	}

	public function gridFilterFactory(Container $c): void
	{
		$c->addText('title');
		$c->addSelect('public')
			->setPrompt('---')
			->setItems([0 => 'Ne', 1 => 'Ano']);
	}

//	public function processFilters(Nette\Database\Table\Selection $data, array $filters): void
//	{
//		foreach ($filters as $column => $value) {
//			if ($column === 'category_id') {
//				$data->where($column, $value);
//			}
//		}
//
//		unset($filters['category_id']);
//		parent::processFilters($data, $filters);
//	}

}
