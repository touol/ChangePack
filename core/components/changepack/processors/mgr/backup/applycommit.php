<?php
$modx->getService('lexicon','modLexicon');
$modx->lexicon->load($modx->config['manager_language'].':changepack:default');
require_once MODX_CORE_PATH."components/changepack/model/changepack/changepack.class.php";
$changepack = new ChangePack($modx, array());

//$changepack->log('info','Error: '.print_r($scriptProperties,true));
if(isset($scriptProperties['id'])){
	if($changepack->getDataFile('file_commit', $scriptProperties['id'])){
		$changepack->applyCommit();
	}
}

$changepack->log('complete','');

return $modx->error->success();
?>