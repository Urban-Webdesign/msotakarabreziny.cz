<?php declare(strict_types = 1);

namespace App\AdminModule\Grid;

use App\Model\AuthorModel;
use K2D\Core\AdminModule\Grid\BaseV2Grid;
use App\Model\CategoryModel;
use App\Model\NewModel;
use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Forms\Container;
use function unlink;

class NewGrid extends BaseV2Grid
{

	private NewModel $newModel;

    private CategoryModel $categoryModel;

    private AuthorModel $authorModel;

	public function __construct(NewModel $newModel, CategoryModel $categoryModel, AuthorModel $authorModel)
	{
		parent::__construct();
		$this->newModel = $newModel;
        $this->authorModel = $authorModel;
		$this->categoryModel = $categoryModel;
	}

	protected function build(): void
	{
		$this->model = $this->newModel;

		parent::build();

		$this->setDefaultOrderBy('created', true);
		$this->setFilterFactory([$this, 'gridFilterFactory']);

		$this->addColumn('title', 'Nadpis');
        $this->addColumn('category_id', 'Kategorie');
		$this->addColumn('contents', 'Obsah');

		if ($this->presenter->configuration->getLanguagesCount() > 1) {
			$this->addColumn('lang', 'Jazyk');
		}

		$this->addColumn('created', 'Vytvořeno')->setSortable();
        $this->addColumn('author_id', 'Autor');
		$this->addColumn('public', 'Veřejná');
		$this->addColumn('gallery_id', 'Galerie');

		$this->addRowAction('edit', 'Upravit', static function (): void {
		});
		$this->addRowAction('delete', 'Smazat', static function (ActiveRow $record): void {
			if ($record->cover) {
				unlink(WWW . '/upload/news/' . $record->id . '/' . $record->cover);
			}

			$record->delete();
		})
			->setProtected(false)
			->setConfirmation('Opravdu chcete novinku smazat?');

		$this->hotFilters = ['title', 'category_id', 'author_id', 'public'];
	}

	public function gridFilterFactory(Container $c): void
	{
		$c->addText('title', 'Nadpis')->setHtmlAttribute('placeholder', 'Filtrovat dle nadpisu');
		$c->addSelect('category_id')
			->setPrompt('Filtrovat dle kategorie')
            ->setItems([
                1 => 'Aktuality',
                2 => 'Sluníčka',
                3 => 'Rybičky',
                4 => 'Veverky',
                5 => 'Broučči'
            ]);
        $c->addSelect('author_id')
            ->setPrompt('Filtrovat dle autora')
            ->setItems($this->authorModel->getForSelect());
		$c->addSelect('public')
			->setPrompt('Zveřejněno')
			->setItems([0 => 'Ne', 1 => 'Ano']);
	}

	public function processFilters(Nette\Database\Table\Selection $data, array $filters): void
	{
		foreach ($filters as $column => $value) {
			if ($column === 'category_id') {
				$data->where($column, $value);
			}
		}

		unset($filters['category_id']);
		parent::processFilters($data, $filters);
	}

}
