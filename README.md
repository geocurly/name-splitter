# name-splitter

There is a name split utility. It's take input string and parse it to the object:

```php
$splitter = new NameSplitter();
$result = $splitter->split('Иванов Иван Иванович');
[$surname, $name, $middleName] = [
    $result->getSurname(),
    $result->getName(),
    $result->getMiddleName(),
];
``` 

The development is in process.