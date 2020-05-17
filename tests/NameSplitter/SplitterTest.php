<?php

declare(strict_types=1);

namespace Tests\NameSplitter;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\NameSplitter;
use PHPUnit\Framework\TestCase;

/**
 * Class SplitterTest
 */
class SplitterTest extends TestCase
{
    /**
     * @dataProvider getTripleProvider
     * @covers       \NameSplitter\NameSplitter::split
     * @param string $name
     * @param ResultInterface $expected
     */
    public function testTripleSplit(string $name, ResultInterface $expected): void
    {
        $splitter = new NameSplitter();
        $result = $splitter->split($name);
        $this->assertSame(
            [
                $expected->getSurname(),
                $expected->getName(),
                $expected->getMiddleName(),
                $expected->getInitials(),
                $expected->getGender(),
            ],
            [
                $result->getSurname(),
                $result->getName(),
                $result->getMiddleName(),
                $result->getInitials(),
                $result->getGender(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getTripleProvider(): array
    {
        return [
            [
                'Близоруков А. С.',
                $this->makeResult(['Близоруков', null, null, 'А.С.', null])
            ],
            [
                'Близоруков Александр С.',
                $this->makeResult(['Близоруков', 'Александр', null, 'С.', ResultInterface::GENDER_MALE])
            ],
            [
                'Иннокентий И. Держ',
                $this->makeResult(['Держ', 'Иннокентий', null, 'И.', ResultInterface::GENDER_MALE])
            ],
            [
                'Близоруков Александр Сергеевич',
                $this->makeResult(['Близоруков', 'Александр' ,'Сергеевич', null, ResultInterface::GENDER_MALE])
            ],
            [
                'Александр Сергеевич Близоруков',
                $this->makeResult(['Близоруков', 'Александр' ,'Сергеевич',null, ResultInterface::GENDER_MALE])
            ],
            [
                'Ивано-иванов Иван Иванович',
                $this->makeResult(['Ивано-иванов', 'Иван' ,'Иванович', null, ResultInterface::GENDER_MALE])
            ],
            [
                'Ивано - иванов Иван Иванович',
                $this->makeResult(['Ивано-иванов', 'Иван' ,'Иванович', null, ResultInterface::GENDER_MALE])
            ],
            [
                'Иванова Анастасия Ивановна',
                $this->makeResult(['Иванова', 'Анастасия' ,'Ивановна', null, ResultInterface::GENDER_FEMALE])
            ],
            [
                'Дрожева Дроже Дрожевна',
                $this->makeResult(['Дрожева', 'Дроже' ,'Дрожевна', null, ResultInterface::GENDER_FEMALE])
            ],
            [
                'Крупич Иван Иванович',
                $this->makeResult(['Крупич', 'Иван' ,'Иванович', null, ResultInterface::GENDER_MALE])
            ],
            [
                'Иван Иванович Крупич',
                $this->makeResult(['Крупич', 'Иван' ,'Иванович', null, ResultInterface::GENDER_MALE])
            ],
        ];
    }

    /**
     * @param array $result
     * @return ResultInterface
     */
    private function makeResult(array $result): ResultInterface
    {
        $methods = [
            'getSurname' => $result[0] ?? null,
            'getName' => $result[1] ?? null,
            'getMiddleName' => $result[2] ?? null,
            'getInitials' => $result[3] ?? null,
            'getGender' => $result[4] ?? null,
        ];

        $mock = $this->getMockBuilder(ResultInterface::class)->getMock();
        foreach ($methods as $name => $value) {
            $mock->method($name)->willReturn($value);
        }

        return $mock;
    }
}