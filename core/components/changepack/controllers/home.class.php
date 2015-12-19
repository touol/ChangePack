<?php

/**
 * The home manager controller for ChangePack.
 *
 */
class ChangePackHomeManagerController extends ChangePackMainController {
	/* @var ChangePack $ChangePack */
	public $ChangePack;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('changepack');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->ChangePack->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->ChangePack->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/commits.grid.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/logs.grid.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/backups.grid.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/commits.windows.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/widgets/backup.form.js');
		$this->addJavascript($this->ChangePack->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "changepack-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->ChangePack->config['templatesPath'] . 'home.tpl';
	}
}