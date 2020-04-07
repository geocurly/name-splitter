<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;

/**
 * Class SinglePart
 * @package NameSplitter\Transformer
 */
class SinglePart implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(StateInterface $state): ?TransformerInterface
    {
        return null;
    }
}