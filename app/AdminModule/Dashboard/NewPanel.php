<?php declare(strict_types = 1);

namespace App\AdminModule\Dashboard;

use K2D\Core\AdminModule\Component\DashboardControl\Panel;
use App\Model\NewModel;

class NewPanel extends Panel
{

	private NewModel $newModel;

	public function __construct(NewModel $newModel)
	{
		$this->newModel = $newModel;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/NewPanel.latte');
		$this->template->count = $this->newModel->getCount();
		$this->template->lastNew = $this->newModel->getTable()->order('created DESC, id DESC')->limit(1)->fetch();
		$this->template->render();
	}

}
