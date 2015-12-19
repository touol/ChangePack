<?php

/**
 * Get a list of Backups
 */
class ChangePackBackupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'ChangePackBackup';
	public $classKey = 'ChangePackBackup';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
	//public $permission = 'list';


	/**
	 * * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return boolean|string
	 */
	public function beforeQuery() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$query = trim($this->getProperty('query'));
		$c->leftJoin('modUser','modUser', '`'.$this->classKey.'`.`user_id` = `modUser`.`id`');
		$orderColumns = $this->modx->getSelectColumns($this->classKey, $this->classKey, '', array(), true);
		$c->select($orderColumns . ', `modUser`.`username` as `customer`');
		if ($query) {
			$c->where(array(
				'name:LIKE' => "%{$query}%",
				'OR:description:LIKE' => "%{$query}%",
			));
		}

		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();
		$array['actions'] = array();

		// Apply
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-minus-square',
			'title' => $this->modx->lexicon('changepack_backup_backupapply'),
			//'multiple' => $this->modx->lexicon('changepack_backups_update'),
			'action' => 'applyBackup',
			'button' => true,
			'menu' => true,
		);
		
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-plus-square',
			'title' => $this->modx->lexicon('changepack_backup_commitapply'),
			//'multiple' => $this->modx->lexicon('changepack_backups_update'),
			'action' => 'applyCommit',
			'button' => true,
			'menu' => true,
		);
		
		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('changepack_backup_remove'),
			'multiple' => $this->modx->lexicon('changepack_backups_remove'),
			'action' => 'removeBackup',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'ChangePackBackupGetListProcessor';