<?php

/**
 * The base class for ChangePack.
 */
class ChangePack {
	/* @var modX $modx */
	public $modx;
	public $data;
	public $backup = array();
	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('changepack_core_path', $config, $this->modx->getOption('core_path') . 'components/changepack/');
		$assetsUrl = $this->modx->getOption('changepack_assets_url', $config, $this->modx->getOption('assets_url') . 'components/changepack/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('changepack', $this->config['modelPath']);
		$this->modx->lexicon->load('changepack:default');
	}
	
	public function addChangePackLog($res, $class, $action){
		$classname = 'ChangePackLog';
		$last = true;
		if($test = $this->modx->getObject($classname, array('commit_id' => 0, 'mod_class' => $class, 'mod_id' => $res->id, last => true))){
			if($action==='upd' and $test->action === 'new'){
				$last = false;
			}elseif($action==='del' and $test->action === 'new'){
				$last = false;
				$test->last = false;
				$test->save();
			}else{
				$test->last = false;
				$test->save();
			}
		}
		$userId = $this->modx->user->get('id');
		
		if($class === 'modResource') {
			$name = $res->pagetitle;
		}elseif($class === 'modTemplate'){
			$name = $res->templatename;
		}else{
			$name = $res->name;
		}
		$log_data = array(
			'name' => $name,
			'action' => $action,
			'mod_class' => $class,
			'mod_id' => $res->id,
			'user_id' => $userId,
			'last' => $last
		);
		$log = $this->modx->newObject($classname);
		$log->fromArray($log_data);
		$m = $log->save();
		if($m){
			return true;
		}else{
			return false;
		}
	}
	public function getDataFile($type_file, $id) {
		$dir = $this->modx->getOption('assets_path');
		$attachment_path = $dir.'/'.'components/changepack/backup/';
		if($backup = $this->modx->getObject('ChangePackBackup', $id)){
			$temp = $backup->toArray();
			$filename = $temp[$type_file];
			$data = file_get_contents($attachment_path.$filename);
			if ($data === false) { 
				$this->log('error',$this->modx->lexicon('changepack.err.fileuploadfailed'));
				return false;
			}
			$this->data = json_decode($data,true);
			return true;
		}
		$this->log('error',$this->modx->lexicon('changepack.err.fileuploadfailed'));
		return false;
	}
	public function getData() {
        // Handle file uploads
        if (!empty($_FILES['json-file']['name']) && !empty($_FILES['json-file']['tmp_name'])) {
            $this->log('info',$this->modx->lexicon('changepack.log.fileuploadfound',array('filename' => $_FILES['json-file']['name'])));
			$dir = $this->modx->getOption('assets_path');
			$attachment_path = $dir.'/'.'components/changepack/backup/';
			
			$this->backup['file_commit'] = $_FILES['json-file']['name'];
			if(!move_uploaded_file($_FILES['json-file']['tmp_name'], $attachment_path.'commit_'.$this->backup['file_commit'])){
				$this->log('error',$this->modx->lexicon('changepack.err.filemovefailed'));
				return false;
			}
			$data = file_get_contents($attachment_path.'commit_'.$this->backup['file_commit']);
            if ($data === false) { 
				$this->log('error',$this->modx->lexicon('changepack.err.fileuploadfailed'));
				return false;
			}
			$temp = json_decode($data,true);
			$this->data = $temp;
			
			return true;
        }else{
			$this->log('error',$this->modx->lexicon('changepack.err.filenotfailed'));
		}
		return false;
	}
	
	public function applyCommit(){
		$this->log('info',$this->modx->lexicon('changepack.log.applycommit'));
		foreach($this->data['data'] as $change){
			if(isset($change['log']['mod_id'])){
				$log['mod_id'] = $change['log']['mod_id'];
				$log['mod_class'] = $change['log']['mod_class'];
				$log['name'] = $change['log']['name'];
				if($change['log']['action']==='new'){
					if($res = $this->modx->getObject($log['mod_class'], $log['mod_id'])){
						$this->log('error',$this->modx->lexicon('changepack.err.objectnewfailed',array(
							'class' => $log['mod_class'],
							'id'=> $log['mod_id'],
							'name'=> $log['name']
							)));
					}else{
						$res = $this->modx->newObject($log['mod_class']);
						$res->fromArray($change['obj']);
						if($res->save()){
							$this->set_mod_id($log['mod_class'], $log['mod_id'], $res->id);
							
							$this->log('info',$this->modx->lexicon('changepack.log.objectnewsaved',array(
								'class' => $log['mod_class'],
								'id'=> $log['mod_id'],
								'name'=> $log['name']
								)));
						}
					}
				}elseif($change['log']['action']==='del'){
					if($res = $this->modx->getObject($log['mod_class'], $log['mod_id'])){
						if($res->remove()){
							$this->log('info',$this->modx->lexicon('changepack.log.objectremove',array(
								'class' => $log['mod_class'],
								'id'=> $log['mod_id'],
								'name'=> $log['name']
								)));
						}
					}else{
						$this->log('error',$this->modx->lexicon('changepack.err.objectfailed',array(
							'class' => $log['mod_class'],
							'id'=> $log['mod_id'],
							'name'=> $log['name']
							)));
					}
				}else{
					if($res = $this->modx->getObject($log['mod_class'], $log['mod_id'])){
						$res->fromArray($change['obj']);
						if($res->save()){
							$this->log('info',$this->modx->lexicon('changepack.log.objectsaved',array(
								'class' => $log['mod_class'],
								'id'=> $log['mod_id'],
								'name'=> $log['name']
								)));
						}
					}else{
						$this->log('error',$this->modx->lexicon('changepack.err.objectfailed',array(
							'class' => $log['mod_class'],
							'id'=> $log['mod_id'],
							'name'=> $log['name']
							)));
					}
				}
			}
		}
	}
	
	public function saveBackupTable(){
		$this->log('info',$this->modx->lexicon('changepack.log.backupsavetable'));
		$commit = $this->data['commit'];
		//"commit":{"name":"test","description":"","user_id":1,"change_count":3,"filename":"test_1450454269.json"}
		if ($this->modx->getCount('ChangePackBackup', array('name' => $commit['name']))) {
			$this->log('error', $this->modx->lexicon('changepack_backup_err_ae'));
			return false;
		}
		$backup_data = array(
			'name'=> $commit['name'],
			'description'=> $commit['description'],
			'user_id'=> $commit['user_id'],
			'change_count'=> $commit['change_count'],
			'file_commit'=> 'commit_'.$this->backup['file_commit'],
			'file_backup'=> 'backup_'.$this->backup['file_commit'],
		);
		if($res = $this->modx->newObject('ChangePackBackup')){
			$res->fromArray($backup_data);
			if($res->save()){
				$this->log('info',$this->modx->lexicon('changepack.log.backupsaved'));
				return true;
			}
		}
	}
	
	public function createBackupFile(){
		$this->log('info',$this->modx->lexicon('changepack.log.backupfilecreate'));
		$temp = array();
		//$data = $this->data->data;
		$backup = true;

		foreach($this->data['data'] as $change){
			if(isset($change['log']['mod_id'])){
				$log = array();
				$logArr = array();
				$log['mod_id'] = $change['log']['mod_id'];
				$log['mod_class'] = $change['log']['mod_class'];
				$log['name'] = $change['log']['name'];
				if($change['log']['action']==='new'){
					$log['action'] = 'del';
					$logArr['log'] = $log;
					$temp[] = $logArr;
				}else{
					if($change['log']['action'] === 'del'){
						$log['action'] = 'new';
					}
					if($res = $this->modx->getObject($log['mod_class'], $log['mod_id'])){
						$logArr['obj'] = $res->toArray();
						$logArr['log'] = $log;
						$temp[] = $logArr;
					}else{
						$this->log('error',$this->modx->lexicon('changepack.err.objectfailed',array(
							'class' => $log['mod_class'],
							'id'=> $log['mod_id'],
							'name'=> $log['name']
							)));
						$backup = false;
					}
				}
			}
		}
		$commit['data'] = $temp;
		$str = json_encode($commit, true);
		$dir = $this->modx->getOption('assets_path');
		$attachment_path = $dir.'/'.'components/changepack/backup/';
		$fp = fopen($attachment_path . 'backup_'.$this->backup['file_commit'], 'w');
		fputs($fp, $str);
		fclose($fp);
		return $backup;
	}
		
	public function log ($type,$msg) {
        switch ($type) {
            case 'error':
                $this->modx->log(modX::LOG_LEVEL_ERROR,'Error: '.$msg);
                break;
            case 'complete':
                $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
                sleep(1);
                break;
            case 'warn':
                $this->modx->log(modX::LOG_LEVEL_WARN,$msg);
                break;
            default:
            case 'info':
                $this->modx->log(modX::LOG_LEVEL_INFO,$msg);
                break;
        }
    }
	public function set_mod_id($mod_class, $new_id, $old_id){
		$sql = "UPDATE {$this->modx->getTableName($mod_class)} SET `id`=$new_id WHERE  `id`=$old_id";
		$q = $this->modx->prepare($sql);
		$q->execute();
	}
	
}