<?php

declare(strict_types=1);

namespace NameSplitter\Transformer;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TransformerInterface;
use NameSplitter\Settings;

/**
 * Class NameDoublet
 * @package NameSplitter\Transformer
 */
class NameDoublet implements TransformerInterface
{
    /**
     * @param StateInterface $state
     * @return TransformerInterface|null
     */
    public function transform(StateInterface $state): ?TransformerInterface
    {
        $parts = $state->getParts();
        // There is patterns:
        // "%Surname %Name"
        // "%Name %Surname"
        foreach ($parts as $key => $part) {
            $nameDict = Settings::getNameDictionary();
            $gender = null;
            if ($nameDict->maleExists($part)) {
                $gender = ResultInterface::GENDER_MALE;
            } elseif($nameDict->femaleExists($part)) {
                $gender = ResultInterface::GENDER_FEMALE;
            }

            if ($gender !== null) {
                $state->setName($key === 0 ? $parts[0] : $parts[1]);
                $state->setSurname($key === 0 ? $parts[1] : $parts[0]);
                $state->setGender($gender);
            }
        }

        return null;
    }
}