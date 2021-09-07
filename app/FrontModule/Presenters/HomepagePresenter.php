<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use App\Model\PricingModel;
use App\Model\ServiceModel;
use K2D\Gallery\Models\ImageModel;
use Nette\Application\UI\Form;
use Nette\Database\DriverException;
use Nette\Mail\DkimSigner;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class HomepagePresenter extends BasePresenter
{

	/** @inject */
	public PricingModel $pricingModel;

	/** @inject */
	public ImageModel $imageModel;

	/** @inject */
	public ServiceModel $serviceModel;

	public function beforeRender(): void
	{
		parent::beforeRender();
		$vars = $this->configuration->getAllVars();
		$phone = $vars['phone'];
		$email = $vars['email'];
		$address = $vars['address'];
		$ico = $vars['ico'];
		$this->template->phone = $phone;
		$this->template->email = $email;
		$this->template->address = $address;
		$this->template->ico = $ico;
	}

	public function renderDefault(): void
	{
		// Render
		$this->template->pricing = $this->pricingModel->getPricing();
		$this->template->services = $this->serviceModel->getPublicServices();
		$this->template->images = $this->imageModel->getImagesByGallery(1);
	}

	public function renderOrder(): void
	{
		// Render
	}

	public function renderService($slug): void
	{
		$service = $this->serviceModel->getService($slug);
		$this->template->service = $service;

		if ($service->gallery_id != NULL)
			$this->template->images = $this->imageModel->getImagesByGallery($service->gallery_id);

	}


	protected function createComponentContactForm(): Form
	{
		$form = new Form();

		$form->addText('name', 'Jméno a příjmení')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 120)
			->setRequired('Musíte uvést Vaše jméno a příjmení');

		$form->addText('phone', 'Telefonní číslo')
			->setRequired('Musíte uvést telefonní číslo');

		$form->addEmail('email', 'Emailová adresa')
			->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 120)
			->setRequired('Musíte uvést Vaši emailovou adresu');

		$form->addTextArea('message', 'Text zprávy')
		->addRule($form::MAX_LENGTH, 'Zpráva je příliš dlouhá', 10000);

		$form->addInvisibleReCaptcha('recaptcha')
			->setMessage('Jste opravdu člověk?');

		$form->addSubmit('submit', 'Odeslat');

		$form->onSubmit[] = function (Form $form) {
			$this->redrawControl('contactForm');

			try {
				$values = $form->getValues(true);

				if (!empty($values)) {
					$mail = new Message();
					$mail->setFrom($values['email'], $values['name'])
						->addTo('info@filipurban.cz')
						->setSubject('Kalcuc.cz - Zpráva z kontaktního formuláře')
						->setBody($values['message']);

					$options = [
						'domain' => 'nette.org',
						'selector' => 'dkim',
						'privateKey' => file_get_contents('../dkim/dkim.key'),
						'passPhrase' => '****',
					];

					$mailer = new SendmailMailer;
					$mailer->setSigner(new DkimSigner($options));
					$mailer->send($mail);
				}

				$this->flashMessage('Email byl úspěšně odeslán!');
				$this->redirect('this?odeslano=1');

			} catch (DriverException $e) {
				$this->flashMessage('Vaši zprávu se nepodařilo odeslat. Kontaktujte prosím správce webu na info@filipurban.cz', 'danger');
			}
		};

		return $form;
	}

	protected function createComponentCalculator(): Form {
		$form = new Form;

		$form->addText('starting_position', 'Startovní pozice')
			->setDefaultValue('Bezděkov, 438 01')
			->setHtmlAttribute('disabled');
		$form->addText('loading_position', 'Místo nakládky')
			->setHtmlAttribute('placeholder', 'Zadejte platnou adresu')
			->setRequired();
		$form->addText('unloading_position', 'Místo vykládky')
			->setDefaultValue('ČOV Žatec, 438 01')
			->setHtmlAttribute('readonly');
		$form->addText('ending_position', 'Cílová pozice')
			->setDefaultValue('Bezděkov, 438 01')
			->setHtmlAttribute('readonly');
		$form->addInteger('distance', 'Počet kilometrů')
			->setHtmlAttribute('readonly');
		$form->addInteger('transport_price', 'Cena dopravy')
			->setHtmlAttribute('readonly');
		$form->addInteger('waste_amount', 'Počet kubických metrů odpadních vod')
			->setHtmlAttribute('placeholder', 'Zadejte množství')
			->setRequired();
		$form->addInteger('disposal_price', 'Cena likvidace')
			->setHtmlAttribute('readonly');
		$form->addInteger('total_price', 'Celková cena')
			->setHtmlAttribute('readonly');

		$form->onSubmit[] = function (Form $form) {

		};

		return $form;
	}

}
