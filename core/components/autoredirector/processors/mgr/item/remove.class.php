<?php
/**
 * Remove an Item
 */
class autoRedirectorItemRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'arRule';
	public $classKey = 'arRule';
	public $languageTopics = array('autoredirector');

}

return 'autoRedirectorItemRemoveProcessor';