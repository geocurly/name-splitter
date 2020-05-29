<?php

declare(strict_types=1);

namespace Cli;

/**
 * Class CliAnalyzer
 * @package Cli
 */
class CliAnalyzer
{
    /** @var CsvNameIterator $iterator */
    private CsvNameIterator $iterator;

    /**
     * NameTester constructor.
     * @param string $csvPath
     */
    public function __construct(string $csvPath)
    {
        $this->iterator = new CsvNameIterator($csvPath);
    }

    /**
     * @param int $charCount
     * @return array
     */
    public function frequentSuffixes(int $charCount = 3): array
    {
        $result = [];
        foreach ($this->iterator->next() as $triplet) {
            if ($triplet->surname !== null) {
                $surnameSuffix = mb_substr($triplet->surname, -$charCount);
                $cnt = ($result[$surnameSuffix]['surname'] ?? 0) + 1 ;
                $result[$surnameSuffix]['surname'] = $cnt;
            } else {
                continue;
            }

            if ($triplet->name !== null) {
                $nameSuffix = mb_substr($triplet->name, -$charCount);
                if (array_key_exists($nameSuffix, $result)) {
                    $cnt = ($result[$nameSuffix]['name'] ?? 0) + 1 ;
                    $result[$nameSuffix]['name'] = $cnt;
                }
            }

            if ($triplet->middle !== null) {
                $middleSuffix = mb_substr($triplet->middle, -$charCount);
                if (array_key_exists($middleSuffix, $result)) {
                    $cnt = ($result[$middleSuffix]['middle'] ?? 0) + 1 ;
                    $result[$middleSuffix]['middle'] = $cnt;
                }
            }
        }

        uasort($result, fn(array $a, array $b) => $a['surname'] > $b['surname'] ? 1 : -1);
        return $result;
    }
}