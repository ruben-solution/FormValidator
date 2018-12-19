# FormValidator

Makes standard validation more pleasant.

```php
<?php

require __DIR__ . '/FormValidator/Form.php';
require __DIR__ . '/FormValidator/Field.php';
require __DIR__ . '/FormValidator/Validate.php';

use FormValidator\Field;
use FormValidator\Form;

/** @var Form $from */
$form = new Form(
    [
        new Field('some_number', '96831',                'type:string|required:false|regex:/(\d{5})/'),
        new Field('firm',        'Google AG',            'type:string|required:true |max:50'),
        new Field('address',     'SomeFamous street 21', 'type:string|required:false|max:255'),
        new Field('email',       'info@some-mail.com',   'type:email |required:true'),
        new Field('date',        '2018-12-11',           'type:date  |required:true |format:Y-m-d'),
        new Field('color',       'hsl(170, 50%, 45%)',   'type:color |required:true |format:hsl'),
    ]
);

// returns array with the key of all invalid fields
print_r($form->validate());
```
