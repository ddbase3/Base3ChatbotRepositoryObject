<?php declare(strict_types=1);

class ilObjBase3ChatbotRepositoryObject extends ilObjectPlugin {

	public function __construct(int $a_ref_id = 0) {
		parent::__construct($a_ref_id);
	}

	protected function initType(): void {
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

	protected function doCreate(bool $clone_mode = false): void {
	}

	protected function doRead(): void {
	}

	protected function doUpdate(): void {
	}

	protected function doDelete(): void {
	}

	protected function doCloneObject(ilObject2 $new_obj, int $a_target_id, ?int $a_copy_id = null): void {
	}
}
