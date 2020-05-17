<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface TemplateInterface
 * @package NameSplitter\Contract
 */
interface TemplateInterface
{
    /** @var string surname template key */
    public const TPL_SURNAME = '%Surname';
    /** @var string middle name template key */
    public const TPL_MIDDLE_NAME = '%Middle';
    /** @var string name template key */
    public const TPL_NAME = "%Name";
    /** @var string strict initials key */
    public const TPL_INITIALS_STRICT = '%CH.%CH.';

    /** @var string[]  */
    public const TPL_AVAILABLE = [
        self::TPL_NAME,
        self::TPL_SURNAME,
        self::TPL_MIDDLE_NAME,
        self::TPL_INITIALS_STRICT,
    ];

    /** @var string upper char key */
    public const TPL_CHAR_UPPER = '%CH';

    /**
     * Parse template to given state
     * @param string $target a string to split
     * @return array = [
     *     TemplateInterface::TPL_SURNAME => 'MatchedValue or null',
     *     TemplateInterface::TPL_MIDDLE_NAME => 'MatchedValue or null',
     *     TemplateInterface::TPL_NAME => 'MatchedValue or null',
     *     TemplateInterface::TPL_INITIALS_STRICT => 'MatchedValue or null',
     * ]
     */
    public function __invoke(string $target): array;
}
