<?php

declare(strict_types=1);

namespace Tests\NameSplitter;

use NameSplitter\NameSplitter;
use PHPUnit\Framework\TestCase;

/**
 * Class SplitterTest
 */
class SplitterTest extends TestCase
{
    /**
     *
     */
    public function testSplit(): void
    {
        $splitter = new NameSplitter();
        $result = $splitter->split('Близоруков Александр Сергеевич');
        $this->assertSame(
            ['Близоруков', 'Александр', 'Сергеевич'],
            [$result->getSurname(), $result->getName(), $result->getMiddleName()]
        );

        $result = $splitter->split('Александр Сергеевич Близоруков');
        $this->assertSame(
            ['Близоруков', 'Александр', 'Сергеевич'],
            [$result->getSurname(), $result->getName(), $result->getMiddleName()]
        );
    }
}