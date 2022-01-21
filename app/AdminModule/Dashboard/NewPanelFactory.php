<?php declare(strict_types = 1);

namespace App\AdminModule\Dashboard;

interface NewPanelFactory
{

	public function create(): NewPanel;

}
