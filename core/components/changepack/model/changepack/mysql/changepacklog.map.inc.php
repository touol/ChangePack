<?php
$xpdo_meta_map['ChangePackLog']= array (
  'package' => 'changepack',
  'version' => '1.1',
  'table' => 'chpack_log',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'commit_id' => 0,
    'action' => '',
    'mod_class' => '',
    'mod_id' => 0,
    'name' => '',
    'user_id' => 0,
    'last' => 1,
    'data' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'commit_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'action' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '5',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'mod_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '120',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'mod_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'last' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 1,
    ),
    'data' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
  ),
  'indexes' => 
  array (
    'key' => 
    array (
      'alias' => 'key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'commit_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'action' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'mod_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'mod_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'user_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'last' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'commit' => 
    array (
      'class' => 'ChangePackCommit',
      'local' => 'commit_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'user_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
