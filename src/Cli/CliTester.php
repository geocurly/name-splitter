<?php

declare(strict_types=1);

namespace Cli;

use NameSplitter\Contract\TemplateInterface as TPL;
use NameSplitter\NameSplitter;

/**
 * Class CliTester
 * @package Cli
 */
class CliTester
{
    /** @var CsvNameIterator $iterator */
    private CsvNameIterator $iterator;
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
        $this->iterator = new CsvNameIterator($csvPath);
    }

    /**
     * @return iterable
     */
    public function test(): iterable
    {
        $splitter = new NameSplitter();
        foreach ($this->iterator->next() as $nameObject) {
            foreach ($this->prepare($nameObject) as [$template, $testString, $expected]) {
                $result = $splitter->split($testString);
                $real = [
                    '%S' => $result->getSurname(),
                    '%N' => $result->getName(),
                    '%M' => $result->getMiddleName(),
                    '%I' => $result->getInitials(),
                ];

                // compact template to present
                $template = array_map(
                    fn(string $string) => mb_substr($string, 0, 2),
                    explode(' ', $template)
                );

                $template = implode(' ', $template);
                if ($expected === array_values($real)) {
                    yield true => "SUCCESS: $template - $testString";
                    $this->stat[0]++;
                } else {
                    $realString = '';
                    foreach ($real as $key => $value) {
                        if ($value === null) {
                            continue;
                        }

                        $realString .= " $key: $value";
                    }

                    yield false => "ERROR: $template - $testString. " .
                    "[ SPLIT |" . ($realString ? "$realString ]" : 'NOTHING ]');
                }

                $this->stat[1]++;
            }
        }

        $this->accuracy = $this->stat[0] / $this->stat[1] * 100.0;
    }

    /**
     * @param NameTriplet $object
     * @param mixed ...$parts
     * @return array
     */
    private function factory(NameTriplet $object, ...$parts): ?array
    {
        $map = [
            TPL::SURNAME => fn($object) => $object->surname,
            TPL::NAME => fn($object) => $object->name,
            TPL::MIDDLE_NAME => fn($object) => $object->middle,
        ];

        $countMap = [
            TPL::SURNAME => 0,
            TPL::NAME => 1,
            TPL::MIDDLE_NAME => 2,
            TPL::INITIALS_STRICT => 3,
            TPL::INITIALS_SPLIT => 3,
        ];

        $template = [[],[],[null, null, null, null]];
        foreach ($parts as $part) {
            $value = $map[$part]($object);
            if ($value === null) {
                return null;
            }

            $template[0][] = $part;
            $template[1][] = $value;
            $template[2][$countMap[$part]] = $value;
        }

        return [
            implode(' ', $template[0]),
            implode(' ', $template[1]),
            $template[2]
        ];
    }

    /**
     * @param NameTriplet $object
     * @return iterable
     */
    private function prepare(NameTriplet $object): iterable
    {
        $templates = [
            [TPL::SURNAME, TPL::NAME, TPL::MIDDLE_NAME],
            [TPL::NAME, TPL::MIDDLE_NAME, TPL::SURNAME],
            [TPL::NAME, TPL::MIDDLE_NAME],
            [TPL::NAME, TPL::SURNAME],
            [TPL::SURNAME, TPL::NAME],
        ];

        foreach ($templates as $template) {
            $pack = $this->factory($object, ...$template);
            if ($pack === null) {
                continue;
            }

            yield $pack;
        }
    }

    /**
     * @return float
     */
    public function getAccuracy(): float
    {
        return $this->accuracy;
    }

    /**
     * @return array
     */
    public function getCounts(): array
    {
        return $this->stat;
    }
}
