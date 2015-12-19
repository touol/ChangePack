<?php

/**
 * Create an Commit
 */
class ChangePackCommitCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'ChangePackCommit';
	public $classKey = 'ChangePackCommit';
	public $languageTopics = array('changepack');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('changepack_commit_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('changepack_commit_err_ae'));
		}

		return parent::beforeSet();
	}
	    /**
     * Process the Object create processor
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        /* Run the beforeSet method before setting the fields, and allow stoppage */
        $canSave = $this->beforeSet();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }
		

        /* run the before save logic */
        $canSave = $this->beforeSave();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }
		
		$data = $this->getProperties();
		
		$data = $this->getJsonFile($data);
		
        $this->object->fromArray($data);

        /* save element */
        if ($this->object->save() == false) {
            $this->modx->error->checkValidation($this->object);
            return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
        }
		$this->setCommitId($this->object->id);
        $this->logManagerAction();
        return $this->cleanup();
    }
	public function setCommitId($id) {
		$classKeyLog = 'ChangePackLog';
		$logs = $this->modx->getIterator($classKeyLog, array('commit_id' => 0));
		foreach($logs as $log){
			$log->commit_id = $id;
			$log->save();
		}
		return true;
	}
	public function getJsonFile($data) {
		$classKeyLog = 'ChangePackLog';
		$c = $this->modx->newQuery($classKeyLog);
		$c->where( array('commit_id' => 0, last => true) );
		$data['user_id']= $this->modx->user->get('id');
		$data['change_count'] = $this->modx->getCount($classKeyLog, $c);
		$logs = $this->modx->getIterator($classKeyLog, $c);
		unset($data['action']);
		$file_name1 = str_replace(' ', '_', $data['name']);
		$file_name2 = str_replace('/[^A-Za-z]+/', '_', $file_name1) . '_' . time();
		$data['filename'] = $file_name2 . '.json';
		$temp = array();
		foreach($logs as $log){
			//json_decode
			$logArr['log'] = $log->toArray();
			if($log->action === 'del'){
					$temp[] = $logArr;
			}else{
					$res = $this->modx->getObject($log->mod_class, $log->mod_id);
					$logArr['obj'] = $res->toArray();
					$temp[] = $logArr;
			}
		}
		$commit['commit'] = $data;
		$commit['data'] = $temp;
		$str = json_encode($commit, true);
		$dir = $this->modx->getOption('assets_path');
		$attachment_path = $dir.'/'.'components/changepack/commit/';
		$fp = fopen($attachment_path . $file_name2 . '.json', 'w');
		fputs($fp, $str);
		fclose($fp);
		return $data;
	}
}

return 'ChangePackCommitCreateProcessor';