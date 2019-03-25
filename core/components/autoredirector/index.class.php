<?php
/**
 * Class AutoredirectorIndexManagerController
 */
class AutoredirectorIndexManagerController extends modExtraManagerController {
	/** @var autoRedirector $autoRedirector */
	public $autoRedirector;


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('autoredirector');
	}


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('autoredirector_core_path', null, $this->modx->getOption('core_path') . 'components/autoredirector/');
		require_once $corePath . 'model/autoredirector/autoredirector.class.php';

		$this->autoRedirector = new autoRedirector($this->modx);

		$this->addCss($this->autoRedirector->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->autoRedirector->config['jsUrl'] . 'mgr/autoredirector.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			autoRedirector.config = ' . $this->modx->toJSON($this->autoRedirector->config) . ';
			autoRedirector.config.connector_url = "' . $this->autoRedirector->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
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
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('autoredirector:default');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->autoRedirector->config['templatesPath'] . 'home.tpl';
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}
