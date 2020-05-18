<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\TemplateInterface as TPL;
use NameSplitter\SplitState;
use PHPUnit\Framework\TestCase;

/**
 * Class RussianRegexTest
 * @package NameSplitter\Template
 */
class RussianRegexTest extends TestCase
{
    /**
     * @dataProvider templatesProvider
     * @covers       \NameSplitter\Template\RussianRegex::__invoke
     * @param array $expected
     * @param string $target
     */
    public function testRussianRegexInvoke(string $target, array $expected): void
    {
        $regex = new RussianRegex(array_keys($expected));

        $state = new SplitState($target);
        $this->assertSame(
            $expected,
            $regex($state),
        );
    }

    /**
     * @return array
     */
    public function templatesProvider(): array
    {
        return [
            [
                'Близоруков Александр Сергеевич',
                [
                    TPL::SURNAME => 'Близоруков',
                    TPL::NAME => 'Александр',
                    TPL::MIDDLE_NAME => 'Сергеевич',
                ]
            ],
            [
                'Александр Сергеевич Близоруков',
                [
                    TPL::NAME => 'Александр',
                    TPL::MIDDLE_NAME => 'Сергеевич',
                    TPL::SURNAME => 'Близоруков',
                ]
            ],
            [
                'Сергеевич Александр Близоруков',
                [
                    TPL::MIDDLE_NAME => 'Сергеевич',
                    TPL::NAME => 'Александр',
                    TPL::SURNAME => 'Близоруков',
                ]
            ],
            [
                'Близоруков А.С.',
                [
                    TPL::SURNAME => 'Близоруков',
                    TPL::INITIALS_STRICT => 'А.С.'
                ]
            ],
            [
                'А.С. Близоруков',
                [
                    TPL::INITIALS_STRICT => 'А.С.',
                    TPL::SURNAME => 'Близоруков',
                ]
            ],
            [
                'Александр Близоруков',
                [
                    TPL::NAME => 'Александр',
                    TPL::SURNAME => 'Близоруков',
                ]
            ],
            [
                'Александр Сергеевич',
                [
                    TPL::NAME => 'Александр',
                    TPL::MIDDLE_NAME => 'Сергеевич',
                ]
            ],
        ];
    }
}