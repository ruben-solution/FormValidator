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
        new Field('SomeNumber', '968#31',                 'type:string|required:false|regex:/(\d{5})/'), // error
        new Field('Firm',       'Google AG',              'type:string|required:true |max:50'),          // success
        new Field('Address',    'SomeFamous street 21',   'type:string|required:false|max:255'),         // success
        new Field('E-Mail',     'info@some-mail.com',     'type:email |required:true'),                  // success
        new Field('Date',       '2018-13-15',             'type:date  |required:false|format:Y-m-d'),    // error
        new Field('Color',      'hsl(170, 50%, 45%)',     'type:color |required:false|format:hsl'),      // success
        new Field('Gender',     'Anrede',                 ['', 'Herr', 'Frau']),                         // error
    ]
);

print_r($form->validate());
print_r($form->getFields());
