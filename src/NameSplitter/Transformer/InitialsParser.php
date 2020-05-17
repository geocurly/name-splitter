<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;

class InitialsParser implements TransformerInterface
{
    /**
     * @param StateInterface $state
     * @return TransformerInterface|null
     */
    public function transform(StateInterface $state): ?TransformerInterface
    {
        $initials = '';
        $parts = $state->getParts();
        $newParts = [];
        foreach ($parts as $number => $part) {
            // There is just one alphabet character
            if ($state->getLengthPart($number) === 1) {
                $initials .= "$part.";
            } elseif (
                // Or a character and a dot at last
                $state->getLengthPart($number) === 2 &&
                mb_substr($part, -1, 1, 'utf8') === "."
            ) {
                $initials .= $part;
            } else {
                $newParts[0][] = $part;
                $newParts[1][] = $state->getLengthPart($number);
            }
        }

        if ($initials !== '') {
            $state->setInitials($initials);
            $state->setParts(...$newParts);
        }

        return null;
    }
}