<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;

/**
 * Class TriplePart
 * @package NameSplitter\Transformer
 */
class TriplePart implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(StateInterface $state): ?TransformerInterface
    {
        $initialsParser = new InitialsParser();
        $initialsParser->transform($state);

        $parts = $state->getParts();
        $count = count($parts);
        if ($count === 1) {
            $state->setSurname($parts[0]);
            return null;
        }

        if ($count === 2) {
            (new NameDoublet())->transform($state);
            return null;
        }

        (new MiddleNameTriplet())->transform($state);

        return null;
    }
}