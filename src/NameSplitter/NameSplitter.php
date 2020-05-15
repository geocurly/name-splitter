<?php

declare(strict_types=1);

namespace NameSplitter;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\Contract\SplitterInterface;
use NameSplitter\Contract\TransformerInterface;
use NameSplitter\Transformer\DefaultEntry;

/**
 * Class NameSplitter
 * @package NameSplitter
 */
class NameSplitter implements SplitterInterface
{
    /** @var TransformerInterface $entry */
    private TransformerInterface $entry;

    /**
     * NameSplitter constructor.
     * @param TransformerInterface $entry
     */
    public function __construct(TransformerInterface $entry = null)
    {
        $this->entry = $entry ?? new DefaultEntry();
    }

    /**
     * @inheritDoc
     */
    public function split(string $name): ResultInterface
    {
        $chain = new TransformerChain(
            new SplitState($name),
            $this->entry
        );

        return $chain->run();
    }
}