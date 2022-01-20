<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use App\Model\StaffModel;
use K2D\File\Model\FileModel;

class HomepagePresenter extends BasePresenter
{

	/** @var StaffModel */
	private StaffModel $staffModel;

	/** @var FileModel */
	private FileModel $fileModel;


	public function __construct(StaffModel $staffModel, FileModel $fileModel)
	{
		parent::__construct();
		$this->staffModel = $staffModel;
		$this->fileModel = $fileModel;
	}

	public function renderDefault(): void
	{
		// Render
	}

	public function renderShow(string $slug): void
	{
		// Render
	}

	public function renderSlunicka(): void
	{
		// Render
	}

	public function renderRybicky(): void
	{
		// Render
	}

	public function renderVeverky(): void
	{
		// Render
	}

	public function renderBroucci(): void
	{
		// Render
	}

	// pages
	public function renderAbout(): void
	{
		// Render
	}

	public function renderBudget(): void
	{
		// Render
	}

	public function renderProjects(): void
	{
		// Render
	}

	public function renderEvents(): void
	{
		// Render
	}

	public function renderCalendar(): void
	{
		// Render
	}

	public function renderOperation(): void
	{
		// Render
	}

	public function renderClubs(): void
	{
		// Render
	}

	public function renderEating(): void
	{
		// Render
	}

	public function renderMenu(): void
	{
		// Render
	}

	public function renderStaff(): void
	{
		// 1 trida
		$this->template->slunicka = $this->staffModel->getStaffByClass(1);
		// 2 trida
		$this->template->rybicky = $this->staffModel->getStaffByClass(2);
		// 3 trida
		$this->template->veverky = $this->staffModel->getStaffByClass(3);
		// 4 trida
		$this->template->broucci = $this->staffModel->getStaffByClass(4);
	}

	public function renderDocuments(): void
	{
		$this->template->documents = $this->fileModel->getFiles(1);
	}

	public function renderGdpr(): void
	{
		// Render
	}

	public function renderContact(): void
	{
		// Render
	}

}
