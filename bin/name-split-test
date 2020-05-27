#!/usr/bin/env php
<?php

declare(strict_types=1);

use NameSplitter\NameSplitter;

require_once __DIR__ . "/../vendor/autoload.php";


class NameObject {
    public ?string $surname;
    public ?string $name;
    public ?string $middle;

    /**
     * NameObject constructor.
     * @param string $surname
     * @param string $name
     * @param string $middle
     */
    public function __construct(string $surname, string $name, string $middle)
    {
        $this->surname = trim($surname) ?: null;
        $this->name = trim($name) ?: null;
        $this->middle = trim($middle) ?: null;
    }

    /**
     * @return array
     */
    public function getFull(): array
    {
        return [$this->surname, $this->name, $this->middle];
    }
}

/**
 * Csv file iterator
 * Class NameCsv
 */
class NameCsv {
    /** @var SplFileObject $csv */
    private SplFileObject $csv;

    /**
     * NameCsv constructor.
     * @param string $csv
     */
    public function __construct(string $csv)
    {
        $this->csv = new SplFileObject($csv, 'r');
    }

    /**
     * @return iterable
     */
    public function next(): iterable
    {
        while (!$this->csv->eof()) {
            $line = $this->csv->fgetcsv(';');
            if (count($line) === 3) {
                yield new NameObject(...$line);
            }
        }
    }
}

class NameTester {
    /** @var NameCsv $iterator */
    private NameCsv $iterator;
    /** @var array $stat */
    private array $stat = [0, 0];
    /** @var float $accuracy */
    private float $accuracy = 0.0;

    /**
     * NameTester constructor.
     * @param string $csvPath
     */
    public function __construct(string $csvPath)
    {
        $this->iterator = new NameCsv($csvPath);
    }

    /**
     * @return iterable
     */
    public function test(): iterable
    {
        $splitter = new NameSplitter();
        foreach ($this->iterator->next() as $nameObject) {
            foreach ($this->prepare($nameObject) as [$testString, $expected]) {
                $result = $splitter->split($testString);
                $real = [
                    $result->getSurname(),
                    $result->getName(),
                    $result->getMiddleName(),
                    $result->getInitials(),
                ];

                $isSame = $expected === $real;
                if ($isSame) {
                    yield true => "SUCCESS: $testString";
                    $this->stat[0]++;
                } else {
                    yield false => "ERROR: $testString";
                }

                $this->stat[1]++;
            }
        }

        $this->accuracy = $this->stat[0] / $this->stat[1] * 100.0;
    }

    /**
     * @param NameObject $object
     * @return iterable
     */
    private function prepare(NameObject $object): iterable
    {
        foreach (['full'] as $method) {
            $pack = $this->{$method}($object);
            if ($pack === null) {
                continue;
            }

            yield $pack;
        }
    }

    /**
     * @param NameObject $object
     * @return array|null [$testString, $expected]
     */
    private function full(NameObject $object): ?array
    {
        $full = array_filter($object->getFull());
        if (count($full) === 3) {
            return [implode(' ', $full), [...$full, null]];
        }

        return null;
    }

    /**
     * @return float
     */
    public function getAccuracy(): float
    {
        return $this->accuracy;
    }
}

$options = getopt('' , ['file:','verbose']);
if (!isset($options['file'])) {
    exit("ERROR: Unknown csv path. Please put csv path to \"--file\" option." . PHP_EOL);
}

$csvPath = realpath($options['file']);
if ($csvPath === false) {
    exit("ERROR: Unknown csv path: $csvPath" . PHP_EOL);
}

$isVerbose = array_key_exists( 'verbose', $options);

$tester = new NameTester($csvPath);
$tester->test();
foreach ($tester->test() as $state => $message) {
    if ($isVerbose && !$state) {
        echo $message . PHP_EOL;
    }
}


echo "RESULT: Accuracy is " . number_format($tester->getAccuracy(), 3) . PHP_EOL;