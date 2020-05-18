<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface;
use NameSplitter\Contract\TemplateInterface as TPL;
use NameSplitter\Settings;

class MiddleNameTriplet implements TemplateInterface
{
    /**
     * @param StateInterface $state
     * @return array
     */
    public function __invoke(StateInterface $state): array
    {
        $parts = $state->getParts();
        // There is could be two patterns:
        // 1) "name" "middle name" "surname"
        // 2) "surname" "name" "middle name"
        if (count($parts) !== 3) {
            return [];
        }

        // find "middle name"
        $middleNameDictionary = Settings::getMiddleNameDictionary();
        foreach ([$parts[1], $parts[2]] as $key => $part) {
            if (
                $middleNameDictionary->maleExists($part) ||
                $middleNameDictionary->femaleExists($part)
            ) {
                return [
                    TPL::SURNAME => $key === 0 ? $parts[2] : $parts[0],
                    TPL::NAME => $key === 0 ? $parts[0] : $parts[1],
                    TPL::MIDDLE_NAME => $part,
                ];
            }
        }

        return [];
    }
}