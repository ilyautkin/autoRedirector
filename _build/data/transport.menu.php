<?php

$menus = array();

$tmp = array(
	'autoredirector' => array(
		'description' => 'autoredirector_menu_desc',
		'action' => 'index',
	),
);

$i = 0;
foreach ($tmp as $k => $v) {

	/* @var modMenu $menu */
	$menu = $modx->newObject('modMenu');
	$menu->fromArray(array_merge(
		array(
			'text' => $k,
			'namespace' => $k,
			'parent' => 'components',
			'icon' => 'images/icons/plugin.gif',
			'menuindex' => $i,
			'params' => '',
			'handler' => '',
		), $v
	), '', true, true);

	$menus[] = $menu;
	$i++;
}

unset($menu, $i);
return $menus;