<?php declare(strict_types = 1);

namespace App\AdminModule\Presenters;

use K2D\Core\AdminModule\Component\CropperComponent\CropperComponent;
use K2D\Core\AdminModule\Component\CropperComponent\CropperComponentFactory;
use K2D\Core\AdminModule\Presenter\BasePresenter;
use K2D\Core\Helper\Helper;
use K2D\File\AdminModule\Component\DropzoneComponent\DropzoneComponent;
use K2D\File\AdminModule\Component\DropzoneComponent\DropzoneComponentFactory;
use App\AdminModule\Grid\StaffGrid;
use App\AdminModule\Grid\StaffGridFactory;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Http\FileUpload;
use App\Model\ClassModel;
use App\Model\StaffModel;
use function file_exists;
use function reset;
use function unlink;

/**
 * @property-read ActiveRow|null $staff
 */
class StaffPresenter extends BasePresenter
{

	/** @inject */
	public StaffModel $staffModel;

	/** @inject */
	public ClassModel $classModel;

	/** @inject */
	public StaffGridFactory $staffGridFactory;

	/** @inject */
	public DropzoneComponentFactory $dropzoneComponentFactory;

	/** @inject */
	public CropperComponentFactory $cropperComponentFactory;

	public function renderEdit(?int $id = null): void
	{
		$this->template->staff = null;

		if ($id !== null && $this->staff !== null) {
			$staff = $this->staff->toArray();

			$form = $this['editForm'];
			$form->setDefaults($staff);

			$this->template->staff = $this->staff;
		}
	}

	public function createComponentEditForm(): Form
	{
		$form = new Form();

		$form->addHidden('id');

		$form->addText('title', 'Titul')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 10);

		$form->addText('name', 'Jméno')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 120)
			->setRequired('Musíte uvést jméno zaměstnance');

		$form->addText('surname', 'Příjmení')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 120)
			->setRequired('Musíte uvést příjmení zaměstnance');

		$form->addSelect('class_id', 'Třída')
			->setItems($this->classModel->getForSelect());

		$form->addEmail('email', 'Email (volitelný)')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 200);

		$form->addText('phone', 'Telefonní číslo (volitelné)')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 30);

		$form->addCheckbox('public', 'Zveřejnit profil na webu')
			->setDefaultValue(true);

		$form->addTextArea('description', 'Popisek (volitelný)', 100, 25)
			->setHtmlAttribute('class', 'form-wysiwyg');

		$form->addSubmit('save', 'Uložit');

		$form->onSubmit[] = function (Form $form): void {
			$values = $form->getValues(true);

			$staff = $this->staff;

			if ($values['id'] === '') {
				unset($values['id']);
				$values['id'] = $this->staffModel->insert($values)->id;
				$this->flashMessage('Profil vytvořen', 'success');
			} else {
				$this->staffModel->get($values['id'])->update($values);
				$this->flashMessage('Profil upraven', 'success');
			}

			$this->redirect('this', ['id' => $values['id']]);
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
			$link = WWW . '/upload/staff/' . $this->staff->id . '/';
			$fileName = Helper::generateFileName($fileUpload);

			if (!file_exists($link)) {
				Helper::mkdir($link);
			}

			if ($image->getHeight() > 540 || $image->getWidth() > 960) {
				$image->resize(540, 960);
			}

			$image->save($link . $fileName);
			$this->staff->update(['cover' => $fileName]);
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
		unlink(WWW . '/upload/staff/' . $this->staff->id . '/' . $this->staff->cover);
		$this->staff->update(['cover' => null]);
		$this->flashMessage('Náhledový obrázek byl smazán');
		$this->redirect('this');
	}

	protected function createComponentStaffGrid(): StaffGrid
	{
		return $this->staffGridFactory->create();
	}

	protected function createComponentDropzone(): DropzoneComponent
	{
		$control = $this->dropzoneComponentFactory->create();
		$control->setPrompt('Nahrajte obrázek přetažením nebo kliknutím sem.');
		$control->setUploadLink($this->link('uploadFiles!'));
		$control->setRedrawLink($this->link('redrawFiles!'));

		return $control;
	}

	protected function createComponentCropper(): CropperComponent
	{
		$cropper = $this->cropperComponentFactory->create();

		if ($this->staff->cover !== null) {
			$cropper->setImagePath('upload/staff/' . $this->staff->id . '/' . $this->staff->cover)
				->setAspectRatio((float) .75);
		}

		$cropper->onCrop[] = function (): void {
			$this->redirect('this');
		};

		return $cropper;
	}

	protected function getStaff(): ?ActiveRow
	{
		return $this->staffModel->get($this->getParameter('id'));
	}

}
