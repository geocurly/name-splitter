<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\TemplateInterface as TPL;
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

        $this->assertSame(
            $expected,
            $regex($target),
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
                    TPL::TPL_SURNAME => 'Близоруков',
                    TPL::TPL_NAME => 'Александр',
                    TPL::TPL_MIDDLE_NAME => 'Сергеевич',
                ]
            ],
            [
                'Александр Сергеевич Близоруков',
                [
                    TPL::TPL_NAME => 'Александр',
                    TPL::TPL_MIDDLE_NAME => 'Сергеевич',
                    TPL::TPL_SURNAME => 'Близоруков',
                ]
            ],
            [
                'Сергеевич Александр Близоруков',
                [
                    TPL::TPL_MIDDLE_NAME => 'Сергеевич',
                    TPL::TPL_NAME => 'Александр',
                    TPL::TPL_SURNAME => 'Близоруков',
                ]
            ],
            [
                'Близоруков А.С.',
                [
                    TPL::TPL_SURNAME => 'Близоруков',
                    TPL::TPL_INITIALS_STRICT => 'А.С.'
                ]
            ],
            [
                'А.С. Близоруков',
                [
                    TPL::TPL_INITIALS_STRICT => 'А.С.',
                    TPL::TPL_SURNAME => 'Близоруков',
                ]
            ],
            [
                'Александр Близоруков',
                [
                    TPL::TPL_NAME => 'Александр',
                    TPL::TPL_SURNAME => 'Близоруков',
                ]
            ],
            [
                'Александр Сергеевич',
                [
                    TPL::TPL_NAME => 'Александр',
                    TPL::TPL_MIDDLE_NAME => 'Сергеевич',
                ]
            ],
        ];
    }
}