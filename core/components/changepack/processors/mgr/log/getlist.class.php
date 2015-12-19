<?php

/**
 * Get a list of Logs
 */
class ChangePackLogGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'ChangePackLog';
	public $classKey = 'ChangePackLog';
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
		$querycommit = trim($this->getProperty('querycommit'));
		$querylast = trim($this->getProperty('querylast'));
		$c->leftJoin('modUser','modUser', '`'.$this->classKey.'`.`user_id` = `modUser`.`id`');
		$orderColumns = $this->modx->getSelectColumns($this->classKey, $this->classKey, '', array(), true);
		$c->select($orderColumns . ', `modUser`.`username` as `customer`');
		if ($querycommit and $querylast) {
			$c->where(array(
				'last:LIKE' => "$querylast",
				'AND:commit_id:LIKE' => "$querycommit",
			));
		}elseif($querycommit){
			$c->where(array(
				'commit_id:LIKE' => "$querycommit",
			));
		}elseif($querylast != ""){
			$c->where(array(
				'last:LIKE' => "$querylast",
			));
		}

		/* $c->prepare();
		echo $c->toSql();
		die; */
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

		// Edit

		if (!$array['last']) {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => 'icon icon-power-off action-green',
				'title' => $this->modx->lexicon('changepack_log_enable'),
				'multiple' => $this->modx->lexicon('changepack_logs_enable'),
				'action' => 'enableLog',
				'button' => true,
				'menu' => true,
			);
		}
		else {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => 'icon icon-power-off action-gray',
				'title' => $this->modx->lexicon('changepack_log_disable'),
				'multiple' => $this->modx->lexicon('changepack_logs_disable'),
				'action' => 'disableLog',
				'button' => true,
				'menu' => true,
			);
		}

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('changepack_log_remove'),
			'multiple' => $this->modx->lexicon('changepack_logs_remove'),
			'action' => 'removeLog',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'ChangePackLogGetListProcessor';