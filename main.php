<?php

/**
 * @author Ruben Allenspach <ruben.allenspach@solution.ch>
 */

require __DIR__ . '/FormValidator/Form.php';
require __DIR__ . '/FormValidator/Field.php';
require __DIR__ . '/FormValidator/Validate.php';

use FormValidator\Field;
use FormValidator\Form;

/** @var Form $from */
$form = new Form(
    [
        new Field('SomeNumber', '96831',                  'type:string|required:false|regex:/(\d{5})/'),
        new Field('Firm',       'Google AG',              'type:string|required:true |max:50'),
        new Field('Address',    'SomeFamous street 21',   'type:string|required:false|max:255'),
        new Field('E-Mail',     'info@some-mail.com',     'type:email |required:true'),
        new Field('Date',       '2018-12-11',             'type:date  |required:false|format:Y-m-d'),
        new Field('Color',      'hsl(170, 50%, 45%)',     'type:color |required:false|format:hsl'),
    ]
);

print_r($form->validate());
