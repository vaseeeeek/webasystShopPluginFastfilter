<?php
return array(
    'shop_fastfilter_settings' => array(
        'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
        'category_id' => array('int', 11, 'unsigned' => 1, 'null' => 0),
        'feature_id' => array('int', 11, 'unsigned' => 1, 'null' => 0),
        'feature_code' => array('text', 11, 'null' => 0),
        'feature_name' => array('text', 11, 'null' => 0),
        'default_values' => array('text', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
            'category_feature' => array('category_id', 'feature_id'),
        ),
    ),
);
