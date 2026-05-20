<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Payment Config
    |--------------------------------------------------------------------------
    | 
    | Global configurations for payments services
    |
    */

    /**
     * Price
     */
    'price' => 10,

    /**
     * Services
     */
    'services' => array(
        /**
         * Lusopay
         */
        'lusopay' => array(
            'entity'       => '11024',
            'key'          => '9CE4639A-5125-4B5E-8160-0C2DFD98AD8A', //test
            'nif'          => '999999999', //or vat number
            'url'          => 'https://services.lusopay.com/PaymentServices_test/PaymentServices.svc?wsdl', //url de testes
            /*'key'          => '22c043f3-add3-40dd-83fe-f0467f23df2e',
            'nif'          => '263798569',
            'url'          => 'https://services.lusopay.com/PaymentServices/PaymentServices.svc?wsdl', //url de produtivo*/
            'service_type' => 'MB' //Both|MB|PS
        ),

        /**
         * Bank Transfer
         */
        "transfer" => array (
            'nib'   => '001000003308571010151',
            'banc'  => 'BPI'
        ),
    )
);