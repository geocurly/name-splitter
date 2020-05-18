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

        foreach ($parts as $key => $part) {
            if (
                Settings::getNameDictionary()->maleExists($part) ||
                Settings::getNameDictionary()->femaleExists($part)
            ) {
                $another = $key === 0 ? $parts[1] : $parts[0];
                $isMiddleName = Settings::getMiddleNameDictionary()->maleExists($another) ||
                    Settings::getMiddleNameDictionary()->femaleExists($another);

                $result[TPL::NAME] = $part;
                if ($isMiddleName) {
                    $result[TPL::MIDDLE_NAME] = $another;
                } else {
                    $result[TPL::SURNAME] = $another;
                }

                return $result;
            }
        }

        return [];
    }
}