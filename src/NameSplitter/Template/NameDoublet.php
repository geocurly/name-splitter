<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface;
use NameSplitter\Contract\TemplateInterface as TPL;
use NameSplitter\Settings;

class NameDoublet implements TemplateInterface
{
    /**
     * @param StateInterface $state
     * @return array
     */
    public function __invoke(StateInterface $state): array
    {
        $parts = $state->getParts();
        if (count($parts) !== 2) {
            return [];
        }

        // Look for surname. Middle name haven't to be on the first position
        $first = $parts[0];
        $gender = null;
        if (Settings::getNameDictionary()->maleExists($first)) {
            $gender = 0;
        } elseif (Settings::getNameDictionary()->femaleExists($first)) {
            $gender = 1;
        }

        if ($gender !== null) {
            $second = $parts[1];
            if (
                $gender === 0 && Settings::getMiddleNameDictionary()->isMaleSuffix($second) ||
                $gender === 1 && Settings::getMiddleNameDictionary()->isFemaleSuffix($second)
            ) {
                // Middle name on the second position
                // Take first as a name
                return [
                    TPL::NAME => $parts[0],
                    TPL::MIDDLE_NAME => $parts[1],
                ];
            } else {
                return [
                    TPL::NAME => $parts[0],
                    TPL::SURNAME => $parts[1],
                ];
            }
        }

        if (
            Settings::getMiddleNameDictionary()->isFemaleSuffix($parts[1]) ||
            Settings::getMiddleNameDictionary()->isMaleSuffix($parts[1])
        ) {
            return [
                TPL::MIDDLE_NAME => $parts[1],
                TPL::NAME => $parts[0],
            ];
        }

        return [
            TPL::SURNAME => $first,
            TPL::NAME => $parts[1],
        ];
    }
}