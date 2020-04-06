<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface TransformerInterface
 * @package NameSplitter\Contract
 */
interface TransformerInterface
{
    /**
     * Transform split state by internal context and return next transformer
     *
     * @param StateInterface $state
     * @return TransformerInterface|null
     */
    public function transform(StateInterface $state): ?TransformerInterface;
}