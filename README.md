# name-splitter

### Unfortunately, utility supports only сyrillic names

There is a name split utility. It's take input string and parse it to the object. 

## Usage:

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

## Quality

The NameSplitter's tests cover ~ 13000 cases of russian names with accuracy 99.65. Every case took a part with many templates, so result cases count was 124283. 
You can run tests with your data set (use `--verbose` option to see templates errors):
```bash
[aleksandr@aleksandr name-splitter]$ ./bin/name-split-test --file=$(realpath fio.csv)

TESTED TEMPLATES:
%Surname %Name %Middle
%Name %Middle %Surname
%Name %Middle
%Name %Surname
%Surname %Name
%Surname %StrictInitials
%StrictInitials %Surname
%Surname %SplitInitials
%SplitInitials %Surname

ACCURACY: 99.65
COUNT CASE TOTAL: 124283
COUNT CASE PASS:  123848
COUNT CASE ERROR: 435
```
Format for `fio.csv` file is:
```csv
SomeSurname;SomeName;SomeMiddleName
``` 

## Problems
* Utility can't recognize templates like `%Name %Surname` when surname matches with middle name (for example `Иван Иванович`).
* Some templates may not correctly work when split name doesn't exist in [dictionaries](https://github.com/geocurly/name-splitter/tree/master/resources/dictionaries/ru)

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
            TPL::SURNAME => $surname ?? null, 
            TPL::NAME => $name ?? null,
        ];
    },
];

// There are may be any callable types if they take to input the StateInterface
$after = [];

$splitter = new NameSplitter([], $before, $after);
$result = $splitter->split('Difficult Surname Difficult Name');
``` 
