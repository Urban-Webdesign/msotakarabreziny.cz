<?php declare(strict_types = 1);

namespace App\AdminModule\Grid;

interface NewGridFactory
{

	public function create(): NewGrid;

}
