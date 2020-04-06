<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;

class DefaultEntry implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(StateInterface $state): ?TransformerInterface
    {
        $count = count($state->getParts());
        switch ($count) {
            case 1:
                $next = new SinglePart();
                break;
            case 2:
                $next = new DoublePart();
                break;
            case 3:
                $next = new TriplePart();
                break;
            default:
                $next = null;
                break;
        }

        return $next ?? null;
    }
}