<?php
$xpdo_meta_map['arRule']= array (
  'package' => 'autoredirector',
  'version' => '1.1',
  'table' => 'ar_rules',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'res_id' => 0,
    'context_key' => 'web',
    'uri' => '',
  ),
  'fieldMeta' => 
  array (
    'res_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'web',
    ),
    'uri' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'index' => 'fulltext',
      'default' => '',
    ),
  ),
);
