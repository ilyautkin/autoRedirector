<?php
/**
 * Get an Item
 */
class autoRedirectorItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'arRule';
	public $classKey = 'arRule';
	public $languageTopics = array('autoredirector:default');
}

return 'autoRedirectorItemGetProcessor';