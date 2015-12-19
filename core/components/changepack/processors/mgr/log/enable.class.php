<?php

/**
 * Enable an Log
 */
class ChangePackLogEnableProcessor extends modObjectProcessor {
	public $objectType = 'ChangePackLog';
	public $classKey = 'ChangePackLog';
	public $languageTopics = array('changepack');
	//public $permission = 'save';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('changepack_log_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var ChangePackLog $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('changepack_log_err_nf'));
			}

			$object->set('last', true);
			$object->save();
		}

		return $this->success();
	}

}

return 'ChangePackLogEnableProcessor';
