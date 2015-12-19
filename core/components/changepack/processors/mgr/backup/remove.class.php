<?php

/**
 * Remove an Backups
 */
class ChangePackBackupRemoveProcessor extends modObjectProcessor {
	public $objectType = 'ChangePackBackup';
	public $classKey = 'ChangePackBackup';
	public $languageTopics = array('changepack');
	//public $permission = 'remove';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('changepack_backup_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var ChangePackBackup $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('changepack_backup_err_nf'));
			}
			$this->delFile($object->file_commit);
			$this->delFile($object->file_backup);
			$object->remove();
		}

		return $this->success();
	}
	public function delFile($filename){
		$dir = $this->modx->getOption('assets_path');
		$attachment_path = $dir.'/'.'components/changepack/backup/';
		unlink($attachment_path.$filename); 
	}
}

return 'ChangePackBackupRemoveProcessor';