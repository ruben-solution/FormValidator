# FormValidator

Makes standard validation more pleasant.

```php

/** @var Form $from */
$form = new Form(
    [
        new Field('name',     'Ruben',      'type:string|required:true |max:255'),
        new Field('lastname', 'Allenspach', 'type:string|required:false|max:255'),
    ]
);

$errors = $form->validate();

if (count($errors) > 0) {
    // error handling
}

```

## Usage

This is not a composer package (yet), so you habe to include the scripts manually.

```php

require __DIR__ . '/FormValidator/Form.php';
require __DIR__ . '/FormValidator/Field.php';
require __DIR__ . '/FormValidator/Validate.php';

use FormValidator\Field;
use FormValidator\Form;

```

If you do not want to use namespaces, you can remove them from all the files.

After that, you create a new form with fields:

```php

/** @var Form */
$form = new Form(
    [
        new Field('number_one', '968#31',               'type:string|required:false|regex:/(\d{5})/'),
        new Field('address',    'SomeFamous street 21', 'type:string|required:false|max:255'),
        new Field('email',      'info@some-mail.com',   'type:email |required:true'),
        new Field('date',       '2018-13-15',           'type:date  |required:false|format:Y-m-d'),
        new Field('color',      'hsl(170, 50%, 45%)',   'type:color |required:false|format:hsl'),
        new Field('gender',     'Anrede',               ['', 'Herr', 'Frau']),
        new Field('number_two', '135.2',                'type:number|required:true|max:135.1|min:5.024')
    ]
);

```

To further add new fields later in the script, you can use the method `addField(Field $field)`:

```php

$form->addField(
    new Field(
        'new_field',
        'Lorem',
        'type:string|required:true|max:1024',
        'Additional Field'
    )
);

```

A Field can have one more parameter, that acts like a title, which one can use to generate emails or tables.

Now, to validate the form, simply run:

```php

$errors = $form->validate();

```

`$errors` will hold all field keys, that were no successfully validated. In this case, it will look like this:

```

Array
(
    [0] => number_one
    [1] => date
    [2] => gender
    [3] => number_two
)

```

To get a specific Field use the method `getField($key)` on `$form`. The Field object itself has some options to retrive data from it:

```php

/** @var Field */
$field = $form->getField('date');

$field->getKey();   // 'new_field'
$field->getValue(); // 'Lorem'
$field->getRules(); // 'type:string|required:true|max:1024'
$field->getName();  // 'Additional Field'

```

To get all field data in one array (much faster than one by one) use `getFields()`:

```php

$all_fields = $form->getFields();

/*

Output:

Array
(
    [0] => Array
        (
            [key] => number_one
            [value] => 968#31
            [rules] => type:string|required:false|regex:/(\d{5})/
            [name] =>
        )

    [1] => Array
        (
            [key] => address
            [value] => SomeFamous street 21
            [rules] => type:string|required:false|max:255
            [name] =>
        )
    .
    .
    .
)

*/

```

### Types and possible Filters

- type _string_
    - required `bool`
    - regex `string`
    - min `int`
    - max `int`
- type _number_
    - required `bool`
    - min `float`
    - max `float`
    - nonzero `bool`
- type _color_
    - required `bool`
    - format `string`
- type _date_
    - required `bool`
    - format `string`
- type _email_
    - required `bool`
- type _url_
    - required `bool`
