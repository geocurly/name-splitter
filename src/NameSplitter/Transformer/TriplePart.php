<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;
use NameSplitter\Settings;

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
        $parts = $state->getParts();
        // There is could be two patterns:
        // 1) "name" "middle name" "surname"
        // 2) "surname" "name" "middle name"

        // find "middle name"
        $middleNameDictionary = Settings::getMiddleNameDictionary();
        foreach ([$parts[1], $parts[2]] as $key => $part) {
            $gender = null;
            if ($middleNameDictionary->maleExists($part)) {
                $gender = ResultInterface::GENDER_MALE;
            } elseif ($middleNameDictionary->femaleExists($part)) {
                $gender = ResultInterface::GENDER_FEMALE;
            }

            if ($gender !== null) {
                $state->setGender($gender);
                $state->setMiddleName($part);
                $state->setName($key === 0 ? $parts[0] : $parts[1]);
                $state->setSurname($key === 0 ? $parts[2] : $parts[0]);

                break;
            }
        }

        return null;
    }
}