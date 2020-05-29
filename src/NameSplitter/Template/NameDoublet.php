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
     * The most frequent suffixes for russian surname
     */
    private const SURNAME_FREQUENT_SUFFIXES = [
        'ев',
        'ов',
        'ва',
        'ко',
        'ая',
        'ян',
        'нин',
        'лин',
        'рин',
        'кин',
        'кий',
        'шин',
        'хин',
        'гин',
        'чук',
        'ких',
        'кина',
        'штейн',
        'ллина',
    ];

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
            }

            return [
                TPL::NAME => $parts[0],
                TPL::SURNAME => $parts[1],
            ];
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

        $surnameSuffix = self::SURNAME_FREQUENT_SUFFIXES;
        $regex = "/^([а-яА-ЯёЁ]+(\s*-\s*)?)+(" . implode('|', $surnameSuffix) . ")$/iu";
        foreach ($parts as $key => $part) {
            if (preg_match($regex, $part) === 1) {
                return [
                    TPL::SURNAME => $key === 0 ? $parts[0] : $parts[1],
                    TPL::NAME => $key === 1 ? $parts[0] : $parts[1],
                ];
            }
        }

        return [
            TPL::SURNAME => $first,
            TPL::NAME => $parts[1],
        ];
    }
}