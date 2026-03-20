<?php declare(strict_types=1);

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use ILIAS\DI\Container;

/**
 * @ilCtrl_isCalledBy ilObjBase3ChatbotRepositoryObjectGUI: ilRepositoryGUI, ilAdministrationGUI, ilObjPluginDispatchGUI
 * @ilCtrl_Calls ilObjBase3ChatbotRepositoryObjectGUI: ilPermissionGUI, ilInfoScreenGUI, ilObjectCopyGUI, ilCommonActionDispatcherGUI
 */
class ilObjBase3ChatbotRepositoryObjectGUI extends ilObjectPluginGUI {

	public function getType(): string {
		return ilBase3ChatbotRepositoryObjectPlugin::ID;
	}

	public function getAfterCreationCmd(): string {
		return 'editSettings';
	}

	public function getStandardCmd(): string {
		return 'showContent';
	}

	public function performCommand(string $cmd): void {
		switch ($cmd) {
			case 'showContent':
				$this->checkPermission('read');
				$this->showContent();
				break;

			case 'editSettings':
				$this->checkPermission('write');
				$this->editSettings();
				break;

			case 'saveSettings':
				$this->checkPermission('write');
				$this->saveSettings();
				break;

			default:
				$this->checkPermission('read');
				$this->showContent();
				break;
		}
	}

	protected function setTabs(): void {
		$this->tabs->addTab(
			'content',
			'Content',
			$this->ctrl->getLinkTarget($this, 'showContent')
		);

		$this->tabs->addTab(
			'settings',
			'Settings',
			$this->ctrl->getLinkTarget($this, 'editSettings')
		);

		$this->addInfoTab();
		$this->addPermissionTab();
	}

	protected function showContent(): void {
		$props = $this->getDisplayProps();

		if (($props['service'] ?? '') === '') {
			$this->tpl->setContent(
				'<div class="alert alert-info">No service endpoint configured yet.</div>'
			);
			return;
		}

		$dic = $this->getDic();
		$dic->ui()->mainTemplate()->addJavaScript('components/Base3/ClientStack/assetloader/assetloader.min.js');

		$classmap = $dic[IClassMap::class];
		$displays = $classmap->getInstances([
			'interface' => IDisplay::class,
			'name' => 'iliaschatbotdisplay'
		]);

		if (empty($displays)) {
			$this->tpl->setContent('Display not found.');
			return;
		}

		$display = $displays[0];
		$display->setData($props);

		$this->tpl->setContent($display->getOutput());
	}

	protected function editSettings(): void {
		$form = $this->initSettingsForm();
		$this->tpl->setContent($form->getHTML());
	}

	protected function saveSettings(): void {
		$form = $this->initSettingsForm();

		if (!$form->checkInput()) {
			$form->setValuesByPost();
			$this->tpl->setContent($form->getHTML());
			return;
		}

		$props = [
			'service' => (string) $form->getInput('service'),
			'default_lang' => (string) $form->getInput('default_lang')
		];

		$this->saveDisplayProps($props);

		$this->tpl->setOnScreenMessage('success', 'Settings saved.', true);
		$this->ctrl->redirect($this, 'showContent');
	}

	protected function initSettingsForm(): ilPropertyFormGUI {
		$props = $this->getDisplayProps();

		$form = new ilPropertyFormGUI();
		$form->setTitle('Chatbot Settings');

		$service = new ilTextInputGUI('Service Endpoint', 'service');
		$service->setRequired(true);
		$service->setValue((string) ($props['service'] ?? ''));
		$form->addItem($service);

		$defaultLang = new ilTextInputGUI('Language', 'default_lang');
		$defaultLang->setValue((string) ($props['default_lang'] ?? 'de-DE'));
		$form->addItem($defaultLang);

		$form->addCommandButton('saveSettings', 'Save');
		$form->setFormAction($this->ctrl->getFormAction($this));

		return $form;
	}

	protected function getDisplayProps(): array {
		$settings = $this->getSettings();

		return [
			'service' => (string) $settings->get($this->buildSettingKey('service'), ''),
			'default_lang' => (string) $settings->get($this->buildSettingKey('default_lang'), 'de-DE')
		];
	}

	protected function saveDisplayProps(array $props): void {
		$settings = $this->getSettings();

		$settings->set($this->buildSettingKey('service'), (string) ($props['service'] ?? ''));
		$settings->set($this->buildSettingKey('default_lang'), (string) ($props['default_lang'] ?? 'de-DE'));
	}

	protected function buildSettingKey(string $name): string {
		return 'obj_' . $this->object->getId() . '_' . $name;
	}

	protected function getSettings(): ilSetting {
		return new ilSetting('base3chatbotrepositoryobject');
	}

	protected function getDic(): Container {
		return $GLOBALS['DIC'];
	}
}
