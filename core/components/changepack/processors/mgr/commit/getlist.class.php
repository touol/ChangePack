<?php

/**
 * Get a list of Commits
 */
class ChangePackCommitGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'ChangePackCommit';
	public $classKey = 'ChangePackCommit';
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
		$orderColumns = $this->modx->getSelectColumns($this->classKey, $this->classKey, '', '', true);
		$c->select($orderColumns . ', `modUser`.`username` as `customer`');
		if ($query) {
			$c->where(array(
				'name:LIKE' => "%{$query}%",
				'OR:description:LIKE' => "%{$query}%",
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
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-edit',
			'title' => $this->modx->lexicon('changepack_commit_update'),
			//'multiple' => $this->modx->lexicon('changepack_items_update'),
			'action' => 'updateCommit',
			'button' => true,
			'menu' => true,
		);

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('changepack_commit_remove'),
			'multiple' => $this->modx->lexicon('changepack_commits_remove'),
			'action' => 'removeCommit',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'ChangePackCommitGetListProcessor';