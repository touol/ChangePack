<?php
$path = $modx->getOption('changepack.core_path', null, MODX_CORE_PATH . 'components/changepack/');
$changepack = $modx->getService('changepack', 'ChangePack', $path . 'model/changepack/');
if (!$changepack) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load ChangePack from ' . $path);
    return;
}

switch ($modx->event->name) {
	case 'OnDocFormSave':
		$result = $changepack->addChangePackLog($resource, 'modResource', $mode);
        break;
    case 'OnTempFormSave':
		$result = $changepack->addChangePackLog($template, 'modTemplate', $mode);
        break;
    case 'OnTVFormSave':
		$result = $changepack->addChangePackLog($tv, 'modTemplateVar', $mode);
        break;
    case 'OnChunkFormSave':
		$result = $changepack->addChangePackLog($chunk, 'modChunk', $mode);
        break;
    case 'OnSnipFormSave':
		$result = $changepack->addChangePackLog($snippet, 'modSnippet', $mode);
        break;
    case 'OnPluginFormSave':
		$result = $changepack->addChangePackLog($plugin, 'modPlugin', $mode);
        break;
	case 'OnDocFormDelete':
		$result = $changepack->addChangePackLog($resource, 'modResource', 'del');
        break;
    case 'OnTempFormDelete':
		$result = $changepack->addChangePackLog($template, 'modTemplate', 'del');
        break;
    case 'OnTVFormDelete':
		$result = $changepack->addChangePackLog($tv, 'modTemplateVar', 'del');
        break;
    case 'OnChunkFormDelete':
		$result = $changepack->addChangePackLog($chunk, 'modChunk', 'del');
        break;
    case 'OnSnipFormDelete':
		$result = $changepack->addChangePackLog($snippet, 'modSnippet', 'del');
        break;
    case 'OnPluginFormDelete':
		$result = $changepack->addChangePackLog($plugin, 'modPlugin', 'del');
        break;
}
if (isset($result) && $result === true)
    return;
elseif (isset($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[ChangePack] An error occured. Event: '.$eventName.' - Error: '.($result === false) ? 'undefined error' : $result);
    return;
}