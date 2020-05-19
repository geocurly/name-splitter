# name-splitter

### Unfortunately, utility supports only сyrillic names

There is a name split utility. It's take input string and parse it to the object:

```php
<?php

declare(strict_types=1);

use NameSplitter\NameSplitter;

$splitter = new NameSplitter(['enc' => 'CP1251']);
$result = $splitter->split('Иванов Иван Иванович');
[$surname, $name, $middleName] = [
    $result->getSurname(),
    $result->getName(),
    $result->getMiddleName(),
];
``` 

## Problems
* Ulility can't recognize templates like `%Name %Surname` when surname matches with middle name (for example `Иван Иванович`).
* Some templates may not correctly work when split name doesn't exists in [dictionaries](https://github.com/geocurly/name-splitter/tree/master/resources/dictionaries/ru)

## Decision
You can use pre and post templates:

```php
<?php

declare(strict_types=1);

use NameSplitter\{
    NameSplitter,
    Template\SimpleMatch,
    Contract\TemplateInterface as TPL,
    Contract\StateInterface
};

$before = [
    // for this case we explicitly match name parts with template
    new SimpleMatch([
        TPL::SURNAME => 'Difficult Surname', 
        TPL::NAME => 'Difficult Name'
    ]),
    static function(StateInterface $state) {
        // TODO there is will be your implementation
        return [
            TPL::SURNAME => $surname, 
            TPL::NAME => $name,
        ]
    },
];

// There are may be any callable types if they take to input the StateInterface
$after = [];

$splitter = new NameSplitter([], $before, $after);
$result = $splitter->split('Difficult Surname Difficult Name');
``` 
