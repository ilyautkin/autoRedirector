<?php
/**
 * The home manager controller for autoRedirector.
 *
 */
class autoRedirectorHomeManagerController extends autoRedirectorMainController {
	/* @var autoRedirector $autoRedirector */
	public $autoRedirector;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('autoredirector');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addJavascript($this->autoRedirector->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->addJavascript($this->autoRedirector->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->autoRedirector->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "autoredirector-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->autoRedirector->config['templatesPath'] . 'home.tpl';
	}
}