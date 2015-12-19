<?php

/**
 * Update an Commit
 */
class ChangePackCommitUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'ChangePackCommit';
	public $classKey = 'ChangePackCommit';
	public $languageTopics = array('changepack');
	//public $permission = 'save';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return bool|string
	 */
	public function beforeSave() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$id = (int)$this->getProperty('id');
		$name = trim($this->getProperty('name'));
		if (empty($id)) {
			return $this->modx->lexicon('changepack_commit_err_ns');
		}

		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('changepack_commit_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name, 'id:!=' => $id))) {
			$this->modx->error->addField('name', $this->modx->lexicon('changepack_commit_err_ae'));
		}

		return parent::beforeSet();
	}
	
	/**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        /* Run the beforeSet method before setting the fields, and allow stoppage */
        $canSave = $this->beforeSet();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }
		
		
        $this->object->fromArray($this->getProperties());

        /* Run the beforeSave method and allow stoppage */
        $canSave = $this->beforeSave();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }
		$data = $this->getProperties();
		$filename = $this->object->filename;
		$data = $this->updateJsonFile($data, $filename);
		
        /* run object validation */
        if (!$this->object->validate()) {
            /** @var modValidator $validator */
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'],$this->modx->lexicon($message['message']));
                }
            }
        }

        /* run the before save event and allow stoppage */
        $preventSave = $this->fireBeforeSaveEvent();
        if (!empty($preventSave)) {
            return $this->failure($preventSave);
        }
		
		
        if ($this->saveObject() == false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
        }
        $this->afterSave();
        $this->fireAfterSaveEvent();
        $this->logManagerAction();
        return $this->cleanup();
    }
	public function updateJsonFile($data, $filename) {
		$dir = $this->modx->getOption('assets_path');
		$attachment_path = $dir.'/'.'components/changepack/commit/';
		$str = file_get_contents($attachment_path.$filename);
		$temp = json_decode($str,true);
		$temp['commit']['name'] = $data['name'];
		$temp['commit']['description'] = $data['description'];
		$str = json_encode($temp, true);
		$fp = fopen($attachment_path . $filename, 'w');
		fputs($fp, $str);
		fclose($fp);
	}
}

return 'ChangePackCommitUpdateProcessor';
