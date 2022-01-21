<?php declare(strict_types = 1);

namespace App\Service;

use K2D\Core\Models\ConfigurationModel;
use K2D\Core\Models\LogModel;
use K2D\Core\Service\ModelRepository;
use K2D\File\Model\FileModel;
use K2D\File\Model\FolderModel;
use K2D\Gallery\Models\ImageModel;
use K2D\News\Models\CategoryModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * @property-read FolderModel $folder
 * @property-read FileModel $file
 * @property-read CategoryModel $category
 * * @property-read ImageModel $image
 */
class ProjectModelRepository extends ModelRepository {

    // news by category
    public function getNewsByCategory(string $category): ?Selection
    {
        return $this->category->getItemBy($category, 'name')->related('news.category_id');
    }

    public function getNewsByCategoryCount(string $category): int
    {
        return $this->category->getItemBy($category, 'name')->related('news.category_id')->count();
    }

    // category
    public function getCategoryBySlug(string $slug): ?ActiveRow
    {
        return $this->category->getTable()->where('slug', $slug)->fetch();
    }

    // images
    public function getImagesByGallery(int $id): ?Selection
    {
        return $this->image->getTable()->where('gallery_id', $id)->order('position');
    }

    public function getImagesByGalleryCount(int $id): int
    {
        return $this->image->getTable()->where('gallery_id', $id)->count();
    }
}
