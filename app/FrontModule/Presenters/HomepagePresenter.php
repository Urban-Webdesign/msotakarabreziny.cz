<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use App\Model\StaffModel;
use K2D\File\Model\FileModel;
use App\Model\CategoryModel;
use App\Model\NewModel;
use K2D\Gallery\Models\ImageModel;
use Nette\Utils\Paginator;

class HomepagePresenter extends BasePresenter
{

    /** @var NewModel */
    private NewModel $newModel;

    /** @var ImageModel */
    private ImageModel $imageModel;

    /** @var CategoryModel */
    private CategoryModel $categoryModel;

    /** @var StaffModel */
    private StaffModel $staffModel;

    /** @var FileModel */
	private FileModel $fileModel;


	public function __construct(ImageModel $imageModel, CategoryModel $categoryModel, NewModel $newModel, StaffModel $staffModel, FileModel $fileModel)
	{
		parent::__construct();
        $this->imageModel = $imageModel;
		$this->staffModel = $staffModel;
		$this->fileModel = $fileModel;
        $this->newModel = $newModel;
        $this->categoryModel = $categoryModel;
	}

	public function renderDefault(int $page = 1): void
	{
        $newsCount = $this->newModel->getNewsByCategoryCount(1);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

		$news = $this->newModel->getPublicNewsByCategory(1)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
	}

	public function renderShow(string $class, string $slug): void
	{
        $this->template->category = $this->repository->getCategoryBySlug($class);
		$new = $this->newModel->getNew($slug, 'cs');

        $this->template->new = $new;

        if ($new->gallery_id != NULL) {
            $this->template->images = $this->imageModel->getImagesByGallery($new->gallery_id);
	    }
    }

    public function renderAktuality(int $page = 1): void
    {
        $newsCount = $this->newModel->getNewsByCategoryCount(1);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

        $news = $this->newModel->getPublicNewsByCategory(1)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
    }

	public function renderSlunicka(int $page = 1): void
	{
        $newsCount = $this->newModel->getNewsByCategoryCount(2);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

        $news = $this->newModel->getPublicNewsByCategory(2)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
	}

	public function renderRybicky(int $page = 1): void
	{
        $newsCount = $this->newModel->getNewsByCategoryCount(3);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

        $news = $this->newModel->getPublicNewsByCategory(3)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
	}

	public function renderVeverky(int $page = 1): void
	{
        $newsCount = $this->newModel->getNewsByCategoryCount(4);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

        $news = $this->newModel->getPublicNewsByCategory(4)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
	}

	public function renderBroucci(int $page = 1): void
	{
        $newsCount = $this->newModel->getNewsByCategoryCount(5);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setItemCount($newsCount); // celkový počet položek, je-li znám

        $news = $this->newModel->getPublicNewsByCategory(5)->limit($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;
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
        // ostatni
        $this->template->other = $this->staffModel->getStaffByClass(5);
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
