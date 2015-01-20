<?php
/**
 * Remove an Items
 */
class autoRedirectorItemsRemoveProcessor extends modProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'arRule';
	public $classKey = 'arRule';
	public $languageTopics = array('autoredirector');

	public function process() {

        foreach (explode(',',$this->getProperty('items')) as $id) {
            $item = $this->modx->getObject($this->classKey, $id);
            $item->remove();
        }
        
        return $this->success();

	}

}

return 'autoRedirectorItemsRemoveProcessor';