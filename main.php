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
        new Field('SomeNumber', '968#31',               'type:string|required:false|regex:/(\d{5})/'),
        new Field('Address',    'SomeFamous street 21', 'type:string|required:false|max:255'),
        new Field('E-Mail',     'info@some-mail.com',   'type:email |required:true'),
        new Field('Date',       '2018-13-15',           'type:date  |required:false|format:Y-m-d'),
        new Field('Color',      'hsl(170, 50%, 45%)',   'type:color |required:false|format:hsl'),
        new Field('Gender',     'Anrede',               ['', 'Herr', 'Frau']),
        new Field('Number',     '135.2',                'type:number|required:true |max:135.1|min:5.024'),
        new Field('Array',      ['a', 123],             'type:array |required:true |min:1    |max:1'),
    ]
);

print_r($form->validate());

/*
Output:

Array
(
    [0] => SomeNumber
    [1] => Date
    [2] => Gender
    [3] => Number
)
*/

// print_r($form->getFields());
