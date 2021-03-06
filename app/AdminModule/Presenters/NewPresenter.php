<?php declare(strict_types = 1);

namespace App\AdminModule\Presenters;

use App\Model\AuthorModel;
use K2D\Core\AdminModule\Component\CropperComponent\CropperComponent;
use K2D\Core\AdminModule\Component\CropperComponent\CropperComponentFactory;
use K2D\Core\AdminModule\Presenter\BasePresenter;
use K2D\Core\Helper\Helper;
use K2D\File\AdminModule\Component\DropzoneComponent\DropzoneComponent;
use K2D\File\AdminModule\Component\DropzoneComponent\DropzoneComponentFactory;
use K2D\Gallery\Models\GalleryModel;
use App\AdminModule\Grid\NewGrid;
use App\AdminModule\Grid\NewGridFactory;
use App\Model\CategoryModel;
use App\Model\NewModel;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Http\FileUpload;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use function assert;
use function date_create_from_format;
use function file_exists;
use function reset;
use function unlink;

/**
 * @property-read ActiveRow|null $new
 */
class NewPresenter extends BasePresenter
{

	/** @inject */
	public NewModel $news;

	/** @inject */
	public CategoryModel $categories;

    /** @inject */
    public GalleryModel $galleries;

    /** @inject */
    public AuthorModel $authorModel;

    /** @inject */
	public NewGridFactory $newGridFactory;

	/** @inject */
	public DropzoneComponentFactory $dropzoneComponentFactory;

	/** @inject */
	public CropperComponentFactory $cropperComponentFactory;

    public function startup(): void
    {
        parent::startup();

        if (!$this->adminFirewall->isAllowed('new')) {
            $this->accessDenied();
        }
    }

	public function renderEdit(?int $id = null): void
	{
		$this->template->new = null;
        $this->template->userId = $this->adminFirewall->getUser()->id;

        bdump($this->template->userId);
		if ($id !== null && $this->new !== null) {
			$new = $this->new->toArray();

			$date = $new['created'];
            $new['created'] = $date->format('j.n.Y');

			$form = $this['editForm'];
			$form->setDefaults($new);

			$this->template->new = $this->new;
		}
	}

	public function createComponentEditForm(): Form
	{
		$form = new Form();

		$form->addText('title', 'Nadpis:')
			->addRule(Form::MAX_LENGTH, 'Maxim??ln?? d??lka je %s znak??', 255)
			->setRequired('Mus??te uv??st nadpis aktuality');

        $form->addSelect('author_id', 'Autor:')
            ->setItems($this->authorModel->getForSelect())
            ->setDefaultValue($this->adminFirewall->getUser()->id);

		$form->addText('created', 'Datum:')
			->setDefaultValue((new DateTime())->format('j.n.Y'))
			->setRequired('Mus??te uv??st datum aktuality');

        $form->addSelect('category_id', 'Kategorie:')
            ->setRequired('Mus??te zvolit kategorii')
            ->setItems([
                1 => 'Aktuality',
                2 => 'Slun????ka',
                3 => 'Rybi??ky',
                4 => 'Veverky',
                5 => 'Brou????i'
            ]);

        $form->addSelect('category_id2', 'Kategorie 2:')
            ->setItems([
                0 => '-------',
                1 => 'Aktuality',
                2 => 'Slun????ka',
                3 => 'Rybi??ky',
                4 => 'Veverky',
                5 => 'Brou????i'
            ]);

		if ($this->configuration->getLanguagesCount() > 1) {
			$form->addSelect('lang', 'Jazyk:')
				->setItems($this->configuration->languages, false);
		}

		$form->addCheckbox('public', 'Zve??ejnit')
			->setDefaultValue(true);

		$form->addSelect('gallery_id', 'P??ipojit galerii:')
			->setPrompt('????dn??')
			->setItems($this->galleries->getForSelect());

		$form->addTextArea('content', 'Obsah', 100, 25)
			->setHtmlAttribute('class', 'form-wysiwyg');

		$form->addSubmit('save', 'Ulo??it');

		$form->onSubmit[] = function (Form $form): void {
			$values = $form->getValues(true);
            $values['created'] = date_create_from_format('j.n.Y', $values['created'])->setTime(0, 0, 0);
			$values['slug'] = Strings::webalize($values['title']);

			if (!isset($values['lang'])) {
				$values['lang'] = $this->configuration->getDefaultLanguage();
			}

			$new = $this->new;

			if ($new === null) {
				$new = $this->news->insert($values);
				$this->flashMessage('Novinka vytvo??ena');
			} else {
				$new->update($values);
				$this->flashMessage('Novinka upravena');
			}

			$new->update([
				'slug' => $new->id . '-' . $values['slug'],
			]);

			$this->redirect('this', ['id' => $new->id]);
		};

		return $form;
	}

	public function handleUploadFiles(): void
	{
		$fileUploads = $this->getHttpRequest()->getFiles();
		$fileUpload = reset($fileUploads);

		if (!($fileUpload instanceof FileUpload)) {
			return;
		}

		if ($fileUpload->isOk() && $fileUpload->isImage()) {
			$image = $fileUpload->toImage();
			$link = WWW . '/upload/news/';
			$fileName = Helper::generateFileName($fileUpload);

			if (!file_exists($link)) {
				Helper::mkdir($link);
			}

			if ($image->getHeight() > 1_080 || $image->getWidth() > 1_920) {
				$image->resize(1_920, 1_080);
			}

			$image->save($link . $fileName);
			$this->new->update(['cover' => $fileName]);
		}
	}

	public function handleRedrawFiles(): void
	{
		$this->redirect('this');
	}

	public function handleCropImage(): void
	{
		$this->showModal('cropper');
	}

	public function handleDeleteImage(): void
	{
		unlink(WWW . '/upload/news/' . $this->new->cover);
		$this->new->update(['cover' => null]);
		$this->flashMessage('N??hledov?? obr??zek byl smaz??n');
		$this->redirect('this');
	}

	protected function createComponentNewGrid(): NewGrid
	{
		return $this->newGridFactory->create();
	}

	protected function createComponentDropzone(): DropzoneComponent
	{
		$control = $this->dropzoneComponentFactory->create();
		$control->setPrompt('Nahrajte obr??zek p??eta??en??m nebo kliknut??m sem.');
		$control->setUploadLink($this->link('uploadFiles!'));
		$control->setRedrawLink($this->link('redrawFiles!'));

		return $control;
	}

	protected function createComponentCropper(): CropperComponent
	{
		$cropper = $this->cropperComponentFactory->create();

		if ($this->new->cover !== null) {
			$cropper->setImagePath('upload/news/' . $this->new->cover)
				->setAspectRatio((float) $this->configuration->newAspectRatio);
		}

		$cropper->onCrop[] = function (): void {
			$this->redirect('this');
		};

		return $cropper;
	}

	protected function getNew(): ?ActiveRow
	{
		return $this->news->get($this->getParameter('id'));
	}

}
