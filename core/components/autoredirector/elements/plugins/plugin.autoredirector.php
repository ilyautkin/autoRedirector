<?php

switch ($modx->event->name) {

	case 'OnManagerPageInit':
		$cssFile = MODX_ASSETS_URL.'components/autoredirector/css/mgr/main.css';
		$modx->regClientCSS($cssFile);
		break;

    case "OnBeforeDocFormSave":
        $resources = array(
                $resource,
                $modx->getObject('modResource',$resource->get('parent'))
            );
        $resources = array_merge($resources, $modx->getCollection('modResource',$modx->getChildIds($resource->id,50)));
    case "OnResourceBeforeSort":
        if (empty($resources)) {
            foreach ($nodes as $node) {
                $resources[] = $modx->getObject('modResource',$node['id']);
            }
        }
        foreach ($resources as $res) {
            if (!empty($res)) {
                $res->setProperty('old_uri',$res->get('uri'),'autoredirector');
                $res->save();
            }
        }
        break;
    case "OnDocFormSave":
        $resources = array(
                $resource,
                $modx->getObject('modResource',$resource->get('parent'))
            );
        $resources = array_merge($resources, $modx->getCollection('modResource',$modx->getChildIds($resource->id,50)));
    case "OnResourceSort":
        if (empty($resources)) {
            foreach ($nodesAffected as $node) {
                $resources[] = $node;
            }
        }
        $modelPath = $modx->getOption('autoredirector_core_path',null,$modx->getOption('core_path').'components/autoredirector/').'model/';
		$modx->addPackage('autoredirector', $modelPath);
        $processorProps = array('processors_path' => $modx->getOption('autoredirector_core_path',null,$modx->getOption('core_path').'components/autoredirector/').'processors/');
        foreach ($resources as $res) {
            if (!empty($res)) {
                $old_uri = $res->getProperty('old_uri','autoredirector');
                $current_uri = $res->getAliasPath($res->get('alias'));
                if ($old_uri && $current_uri != $old_uri) {
                    $res->setProperty('old_uri',$current_uri,'autoredirector');
                    $res->save();
                    $currentRuleQ = array('uri' => $current_uri);
                    if (!$modx->getOption('global_duplicate_uri_check')) {
                        $currentRuleQ['context_key'] = $res->get('context_key');
                    }
                    if ($currentRule = $modx->getObject('arRule', $currentRuleQ)) {
                        $response = $modx->runProcessor('mgr/item/remove', $currentRule->toArray(), $processorProps);
                        if ($response->isError()) {
                            $modx->log(modX::LOG_LEVEL_ERROR, 'AutoRedirector removing error. Message: '.$response->getMessage());
                        }
                    }
                    $arRule = array('uri' => $old_uri
                        , 'context_key' => $res->get('context_key')
                        , 'res_id' => $res->get('id'));
                    if (!$modx->getObject('arRule', $arRule)) {
                        $response = $modx->runProcessor('mgr/item/create', $arRule, $processorProps);
                        if ($response->isError()) {
                            $modx->log(modX::LOG_LEVEL_ERROR, 'AutoRedirector creating error. Message: '.$response->getMessage());
                        }
                    }
                }
            }
        }
        break;
    case "OnHandleRequest":
        $uri = $_SERVER['REQUEST_URI'];
        $uri = str_replace($modx->getOption("site_url"),"",$uri);
        if (substr($uri, 0, 1) == "/") $uri = substr($uri, 1);

        $RuleQ = array('uri' => $uri);
        if (!$modx->getOption('global_duplicate_uri_check')) {
            $RuleQ['context_key'] = $modx->context->get('key');
        }
        $modelPath = $modx->getOption('autoredirector_core_path',null,$modx->getOption('core_path').'components/autoredirector/').'model/';
    	$modx->addPackage('autoredirector', $modelPath);
        if ($Rule = $modx->getObject('arRule', $RuleQ)) {
            if ($url = $modx->makeUrl($Rule->get('res_id'))) {
                $modx->sendRedirect($url,array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
            }
        }
        break;
}