<?php

declare(strict_types=1);

namespace NameSplitter;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;

/**
 * Class TransformerChain
 * @package NameSplitter
 */
class TransformerChain
{
    /** @var StateInterface $state */
    private StateInterface $state;
    /** @var TransformerInterface $entry - entry pint for transformation */
    private TransformerInterface $entry;
    /**
     * @var TransformerInterface
     */
    private TransformerInterface $transformer;

    /**
     * TransformerChain constructor.
     * @param StateInterface $state
     * @param TransformerInterface $entry
     */
    public function __construct(StateInterface $state, TransformerInterface $entry)
    {
        $this->state = $state;
        $this->entry = $entry;
    }

    /**
     * Start split state transformation
     * @return StateInterface
     */
    public function run(): StateInterface
    {
        $transformer = $this->entry;
        do {
            $transformer = $transformer->transform($this->state);
        } while($transformer !== null);

        return $this->state;
    }
}
