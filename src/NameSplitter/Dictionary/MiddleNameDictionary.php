<?php

declare(strict_types=1);

namespace NameSplitter\Dictionary;

/**
 * Class MiddleNameDictionary
 * @package NameSplitter\Dictionary
 */
class MiddleNameDictionary
{
    /** @var string[] */
    private const MALE_SUFFIX = ["вич", "тич", "пич", "лич", "нич", "мич", "ьич", "кич"];
    /** @var string[]  */
    private const FEMALE_SUFFIX = ["вна", "чна"];

    /** @var string $malePath path to male name dictionary */
    private string $malePath;
    /** @var string $femalePath path to female name dictionary */
    private string $femalePath;
    /** @var array $maleMap */
    private array $maleMap = [];
    /** @var array $femaleMap */
    private array $femaleMap = [];

    /**
     * NameDictionary constructor.
     * @param string $malePath
     * @param string $femalePath
     */
    public function __construct(string $malePath, string $femalePath)
    {
        $this->malePath = $malePath;
        $this->femalePath = $femalePath;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function maleExists(string $name): bool
    {
        return array_key_exists(mb_strtolower($name, 'utf8'), $this->getMaleIndex());
    }

    /**
     * @param string $middleName
     * @return bool
     */
    public function femaleExists(string $middleName): bool
    {
        return array_key_exists(mb_strtolower($middleName, 'utf8'), $this->getFemaleIndex());
    }

    /**
     * @param string $middleName
     * @return bool
     */
    public function isMaleMiddleName(string $middleName): bool
    {
        $suffix = $this->getSuffix(mb_strtolower($middleName, 'utf8'));
        return in_array($suffix, self::MALE_SUFFIX, true);
    }

    /**
     * @param string $middleName
     * @return bool
     */
    public function isFemaleMiddleName(string $middleName): bool
    {
        $suffix = $this->getSuffix(mb_strtolower($middleName, 'utf8'));
        return in_array($suffix, self::FEMALE_SUFFIX, true);
    }

    /**
     * @param string $middleName
     * @return string
     */
    private function getSuffix(string $middleName): string
    {
        return mb_substr($middleName, -3, 3, 'utf8');
    }

    /**
     * Male name map
     * @return array
     */
    private function getMaleIndex(): array
    {
        if ($this->maleMap === []) {
            $this->maleMap = require_once $this->malePath;
        }

        return $this->maleMap;
    }

    /**
     * Female name map
     * @return array
     */
    private function getFemaleIndex(): array
    {
        if ($this->femaleMap === []) {
            $this->femaleMap = require_once $this->femalePath;
        }

        return $this->femaleMap;
    }
}
