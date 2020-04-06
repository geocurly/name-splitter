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
        $this->assertSame([null], [$result->getName()]);
    }
}