<?php

declare(strict_types=1);

namespace Cli;

use SplFileObject;

/**
 * Class CsvNameIterator
 * @package Cli
 */
class CsvNameIterator
{
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
                yield new NameTriplet(...$line);
            }
        }
    }
}
