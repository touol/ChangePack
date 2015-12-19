<?php

/**
 * Class ChangePackMainController
 */
abstract class ChangePackMainController extends modExtraManagerController {
	/** @var ChangePack $ChangePack */
	public $ChangePack;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('changepack_core_path', null, $this->modx->getOption('core_path') . 'components/changepack/');
		require_once $corePath . 'model/changepack/changepack.class.php';

		$this->ChangePack = new ChangePack($this->modx);
		//$this->addCss($this->ChangePack->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/changepack.js');
		$this->addHtml('
		<script type="text/javascript">
			ChangePack.config = ' . $this->modx->toJSON($this->ChangePack->config) . ';
			ChangePack.config.connector_url = "' . $this->ChangePack->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('changepack:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends ChangePackMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}