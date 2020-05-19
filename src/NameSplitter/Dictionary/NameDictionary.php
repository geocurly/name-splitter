<?php

declare(strict_types=1);

namespace NameSplitter\Dictionary;

/**
 * Class NameDictionary
 * @package NameSplitter\Dictionary
 */
class NameDictionary
{
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
        return array_key_exists(mb_strtolower($name, 'UTF-8'), $this->getMaleIndex());
    }

    /**
     * @param string $name
     * @return bool
     */
    public function femaleExists(string $name): bool
    {
        return array_key_exists(mb_strtolower($name, 'UTF-8'), $this->getFemaleIndex());
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
