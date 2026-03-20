<?php declare(strict_types=1);

class ilObjBase3ChatbotRepositoryObjectListGUI extends ilObjectPluginListGUI {

	public function initType(): void {
		$this->setType(ilBase3ChatbotRepositoryObjectPlugin::ID);
	}

	public function getGuiClass(): string {
		return 'ilObjBase3ChatbotRepositoryObjectGUI';
	}

	public function initCommands(): array {
		return [
			[
				'permission' => 'read',
				'cmd' => 'showContent',
				'default' => true
			]
		];
	}
}
