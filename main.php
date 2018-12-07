<?php

require __DIR__ . '/Form/Form.php';
require __DIR__ . '/Form/Field.php';
require __DIR__ . '/Form/Validate.php';

use Form\Field;
use Form\Form;

/** @var Form $from */
$form = new Form(
    [
        new Field(
            'SomeNumber',
            '96831',
            'type:string|required:false|regex:/(\d{5})/'
        ),
        new Field(
            'Firm',
            'Google AG',
            'type:string|required:true|max:50'
        ),
        new Field(
            'Address',
            'SomeFamous street 21',
            'type:string|required:false|max:255'
        ),
        new Field(
            'E-Mail',
            'info@some-mail.com',
            'type:email|required:true'
        ),
        new Field(
            'Date',
            '2018-12-11',
            'type:date|required:true|format:Y-m-d'
        ),
        new Field(
            'Color',
            'hsl(170, 50%, 45%)',
            'type:color|required:true|format:hsl'
        ),
        new Field(
            'Website',
            'https://www.google.ch/',
            'type:url'
        )
    ]
);

print_r($form->validate());
