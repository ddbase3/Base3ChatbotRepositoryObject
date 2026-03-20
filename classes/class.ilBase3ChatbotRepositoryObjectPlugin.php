<?php declare(strict_types=1);

class ilBase3ChatbotRepositoryObjectPlugin extends ilRepositoryObjectPlugin {
    
	public const ID = 'xb3c';

	public function getPluginName(): string {
		return 'Base3ChatbotRepositoryObject';
	}

	protected function uninstallCustom(): void {
		// noop
	}

	public function allowCopy(): bool {
		return true;
	}
}
