<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use App\Model\PricingModel;
use Latte\Engine;
use Nette\Application\UI\Form;
use Nette\Database\DriverException;
use Nette\Mail\DkimSigner;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use Nette\Neon\Neon;

class HomepagePresenter extends BasePresenter
{

	/** @inject */
	public PricingModel $pricingModel;

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
	}

	public function renderOrder(): void
	{
		// Render
	}

	public function renderService($slug): void
	{
		$this->template->slug = $slug;
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

}
