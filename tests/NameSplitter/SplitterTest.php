<?php

declare(strict_types=1);

namespace Tests\NameSplitter;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\Contract\TemplateInterface;
use NameSplitter\NameSplitter;
use NameSplitter\Template\SimpleMatch;
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
            ],
            [
                $result->getSurname(),
                $result->getName(),
                $result->getMiddleName(),
                $result->getInitials(),
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
                $this->makeResult(['Близоруков', null, null, 'А. С.'])
            ],
            [
                'А. С. Близоруков',
                $this->makeResult(['Близоруков', null, null, 'А. С.'])
            ],
            [
                'Близоруков А.С.',
                $this->makeResult(['Близоруков', null, null, 'А.С.'])
            ],
            [
                'А.С. Близоруков',
                $this->makeResult(['Близоруков', null, null, 'А.С.'])
            ],
            [
                'Близоруков Александр Сергеевич',
                $this->makeResult(['Близоруков', 'Александр', 'Сергеевич', null])
            ],
            [
                'Александр Сергеевич Близоруков',
                $this->makeResult(['Близоруков', 'Александр', 'Сергеевич', null])
            ],
            [
                'Александр Сергеевич',
                $this->makeResult([null, 'Александр', 'Сергеевич', null])
            ],
            [
                'Близоруков Александр',
                $this->makeResult(['Близоруков', 'Александр', null, null])
            ],
            [
                'Александр Близоруков',
                $this->makeResult(['Близоруков', 'Александр', null, null])
            ],
            [
                'Близоруков А. С.',
                $this->makeResult(['Близоруков', null, null, 'А. С.'])
            ],
            [
                'А. С. Близоруков',
                $this->makeResult(['Близоруков', null, null, 'А. С.'])
            ],
            [
                'Крайнович К. О.',
                $this->makeResult(['Крайнович', null, null, 'К. О.'])
            ],
            [
                'Крайнович К.О.',
                $this->makeResult(['Крайнович', null, null, 'К.О.'])
            ],
            [
                'К.О. Крайнович',
                $this->makeResult(['Крайнович', null, null, 'К.О.'])
            ],
            [
                'К.  О. Крайнович',
                $this->makeResult(['Крайнович', null, null, 'К.  О.'])
            ],
            [
                'Крайнович Ксения',
                $this->makeResult(['Крайнович', 'Ксения', null, null])
            ],
            [
                'Ксения Крайнович',
                $this->makeResult(['Крайнович', 'Ксения', null, null])
            ],
            [
                'Ксения Олеговна Крайнович',
                $this->makeResult(['Крайнович', 'Ксения', 'Олеговна', null])
            ],
            [
                'Ксения Олеговна Иванович',
                $this->makeResult(['Иванович', 'Ксения', 'Олеговна', null])
            ],
            [
                'Иванович Ксения Олеговна',
                $this->makeResult(['Иванович', 'Ксения', 'Олеговна', null])
            ],
            [
                'Крайнович Ксения Олеговна',
                $this->makeResult(['Крайнович', 'Ксения', 'Олеговна', null])
            ],
            [
                'Ксения Олеговна',
                $this->makeResult([null, 'Ксения', 'Олеговна', null])
            ],
        ];
    }

    /**
     * Check splitting with another encoding
     */
    public function testEncoding(): void
    {
        $full = mb_convert_encoding("Близоруков Александр Сергеевич", 'CP1251', 'UTF-8');
        $splitter = new NameSplitter(['enc' => 'CP1251']);
        $result = $splitter->split($full);
        $this->assertSame(
            explode(' ', $full),
            [
                $result->getSurname(),
                $result->getName(),
                $result->getMiddleName(),
            ]
        );
    }

    /**
     * Check extending before callbacks
     */
    public function testBeforeCallback(): void
    {
        $full = 'Difficult Surname Name Middle Name';
        $callable = new SimpleMatch([
            TemplateInterface::SURNAME => 'Difficult Surname',
            TemplateInterface::NAME => 'Name',
            TemplateInterface::MIDDLE_NAME => 'Middle Name',
        ]);

        $splitter = new NameSplitter([], [$callable]);

        $result = $splitter->split($full);
        $this->assertSame(
            [
                'Difficult Surname',
                'Name',
                'Middle Name',
            ],
            [
                $result->getSurname(),
                $result->getName(),
                $result->getMiddleName(),
            ]
        );
    }


    /**
     * Check extending before callbacks
     */
    public function testAfterCallback(): void
    {
        $full = 'Difficult Surname Name Middle Name';
        $callable = new SimpleMatch([
            TemplateInterface::SURNAME => 'Difficult Surname',
            TemplateInterface::NAME => 'Name',
            TemplateInterface::MIDDLE_NAME => 'Middle Name',
        ]);

        $splitter = new NameSplitter([], [], [$callable]);

        $result = $splitter->split($full);
        $this->assertSame(
            [
                'Difficult Surname',
                'Name',
                'Middle Name',
            ],
            [
                $result->getSurname(),
                $result->getName(),
                $result->getMiddleName(),
            ]
        );
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
        ];

        $mock = $this->getMockBuilder(ResultInterface::class)->getMock();
        foreach ($methods as $name => $value) {
            $mock->method($name)->willReturn($value);
        }

        return $mock;
    }
}