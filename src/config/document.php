<?php return array(

    'default' => array(
        'pdf'   => 'zend',
        'image' => 'snappy'
    ),


    'snappy' => array(
        'pdf' => array(
            'enabled' => true,
            'binary' => base_path() . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64',
            'options' => array(),
        ),
        'image' => array(
            'enabled' => true,
            'binary' => base_path() . '/vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64',
            'options' => array(),
        ),
    )

);