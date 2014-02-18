<?php

/**
 * Class autoRedirectorMainController
 */
abstract class autoRedirectorMainController extends modExtraManagerController {
	/** @var autoRedirector $autoRedirector */
	public $autoRedirector;


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
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('autoredirector:default');
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
class IndexManagerController extends autoRedirectorMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}