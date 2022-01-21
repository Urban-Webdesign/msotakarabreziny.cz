<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use K2D\Gallery\Models\ImageModel;
use Nette\Utils\Paginator;

class GalleryPresenter extends BasePresenter
{

    /** @var ImageModel */
    private ImageModel $imageModel;

    public function __construct(ImageModel $imageModel)
    {
        parent::__construct();
        $this->imageModel = $imageModel;
    }
	public function renderDefault(int $page = 1): void
	{

        $imagesCount = $this->repository->getImagesByGalleryCount(1);

        $paginator = new Paginator;
        $paginator->setPage($page); // číslo aktuální stránky
        $paginator->setItemsPerPage(24); // počet položek na stránce
        $paginator->setItemCount($imagesCount); // celkový počet položek, je-li znám

        $this->template->images = $this->repository->getImagesByGallery(1)->limit($paginator->getLength(), $paginator->getOffset());
        $this->template->paginator = $paginator;

	}
}
