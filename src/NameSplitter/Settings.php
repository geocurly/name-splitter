<?php

declare(strict_types=1);

namespace NameSplitter;

use NameSplitter\Dictionary\MiddleNameDictionary;
use NameSplitter\Dictionary\NameDictionary;

/**
 * Class Settings
 * @package NameSplitter
 */
abstract class Settings
{
    /** @var string male name dictionary key */
    public const DICTIONARY_NAME_MALE = 'name_male';
    /** @var string female name dictionary key */
    public const DICTIONARY_NAME_FEMALE = 'name_female';
    /** @var string male middle name dictionary key */
    public const DICTIONARY_MIDDLE_NAME_MALE = 'middle_name_male';
    /** @var string female middle name dictionary key */
    public const DICTIONARY_MIDDLE_NAME_FEMALE = 'middle_name_female';

    /** @var string  */
    public const DICTIONARIES_DEFAULT_MAP = [
        self::DICTIONARY_NAME_MALE => 'resources/dictionaries/ru/name/male.php',
        self::DICTIONARY_NAME_FEMALE => 'resources/dictionaries/ru/name/female.php',
        self::DICTIONARY_MIDDLE_NAME_MALE => 'resources/dictionaries/ru/middle_name/male.php',
        self::DICTIONARY_MIDDLE_NAME_FEMALE => 'resources/dictionaries/ru/middle_name/female.php',
    ];

    /** @var NameDictionary|null  */
    private static ?NameDictionary $nameDictionary = null;
    /** @var MiddleNameDictionary|null  */
    private static ?MiddleNameDictionary $middleNameDictionary = null;

    /** @var string[] $dictionaryPaths */
    private static array $dictionaryPaths = [];

    /** @var string $root root of the package */
    private static string $root;

    /**
     * Set root of the package
     * @param string $root
     */
    public static function setRoot(string $root): void
    {
        if (!is_dir($root)) {
            throw new \LogicException("Unknown root of the package: $root");
        }

        static::$root = realpath($root);
    }
    
    /**
     * Get package root
     * @return string
     */
    public static function getRoot(): string
    {
        return static::$root ?? __DIR__ . "/../../";
    }

    /**
     * @param string $type
     * @return string
     */
    private static function getDictionaryPath(string $type): string
    {
        if (!array_key_exists($type, self::$dictionaryPaths)) {
            self::$dictionaryPaths[$type] = self::getRoot() . "/" . self::DICTIONARIES_DEFAULT_MAP[$type];
        }

        return self::$dictionaryPaths[$type];
    }

    /**
     * @return NameDictionary
     */
    public static function getNameDictionary(): NameDictionary
    {
        if (static::$nameDictionary === null) {
            static::$nameDictionary = new NameDictionary(
                self::getDictionaryPath(self::DICTIONARY_NAME_MALE),
                self::getDictionaryPath(self::DICTIONARY_NAME_FEMALE),
            );
        }

        return static::$nameDictionary;
    }

    /**
     * @return MiddleNameDictionary
     */
    public static function getMiddleNameDictionary(): MiddleNameDictionary
    {
        if (static::$middleNameDictionary === null) {
            static::$middleNameDictionary = new MiddleNameDictionary(
                self::getDictionaryPath(self::DICTIONARY_MIDDLE_NAME_MALE),
                self::getDictionaryPath(self::DICTIONARY_MIDDLE_NAME_FEMALE),
            );
        }

        return static::$middleNameDictionary;
    }
}
