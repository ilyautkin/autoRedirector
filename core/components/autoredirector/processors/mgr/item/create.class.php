<?php
/**
 * Create an Item
 */
class autoRedirectorItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'arRule';
	public $classKey = 'arRule';
	public $languageTopics = array('autoredirector');


	/**
	 * @return bool
	 */
     /*
	public function beforeSet() {
		$alreadyExists = $this->modx->getObject('arRule', array(
			'uri' => $this->getProperty('uri'),
		));
		if ($alreadyExists) {
			$this->modx->error->addField('uri', $this->modx->lexicon('autoredirector_item_err_ae'));
		}

		return !$this->hasErrors();
	}
*/
}

return 'autoRedirectorItemCreateProcessor';