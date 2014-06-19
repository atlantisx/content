<?php return [

    's3' => [
        /*
        |--------------------------------------------------------------------------
        | S3 Client Config
        |--------------------------------------------------------------------------
        |
        | This is array holds the default configuration options used when creating
        | an instance of Aws\S3\S3Client.  These options will be passed directly to
        | the s3ClientFactory when creating an S3 client instance.
        |
        */
        'client' => [
            'key'       => '',
            'secret'    => '',
            'region'    => '',
            'scheme'    => 'http'
        ],

        /*
        |--------------------------------------------------------------------------
        | S3 Object Config
        |--------------------------------------------------------------------------
        |
        | An array of options used by the Aws\S3\S3Client::putObject() method when
        | storing a file on S3.
        |
        */
        'object' => [
            'Bucket'    => '',
            'ACL'       => 'public-read'
        ],
    ]

];