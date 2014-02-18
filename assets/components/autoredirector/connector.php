<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('autoredirector_core_path', null, $modx->getOption('core_path') . 'components/autoredirector/');
require_once $corePath . 'model/autoredirector/autoredirector.class.php';
$modx->autoredirector = new autoRedirector($modx);

$modx->lexicon->load('autoredirector:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->autoredirector->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));