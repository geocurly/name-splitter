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
    public const SURNAME = '%Surname';
    /** @var string middle name template key */
    public const MIDDLE_NAME = '%Middle';
    /** @var string name template key */
    public const NAME = "%Name";
    /** @var string strict initials key */
    public const INITIALS_STRICT = '%CH.%CH.';
    /** @var string splitted initials key */
    public const INITIALS_SPLITTED = '%CH. %CH.';

    /** @var string[]  */
    public const TPL_AVAILABLE = [
        self::NAME,
        self::SURNAME,
        self::MIDDLE_NAME,
        self::INITIALS_STRICT,
        self::INITIALS_SPLITTED,
    ];

    /**
     * Parse template to given state
     * @param StateInterface $state
     * @return array = [
     *     TemplateInterface::SURNAME => 'MatchedValue or null',
     *     TemplateInterface::MIDDLE_NAME => 'MatchedValue or null',
     *     TemplateInterface::NAME => 'MatchedValue or null',
     *     TemplateInterface::INITIALS_STRICT => 'MatchedValue or null',
     * ]
     */
    public function __invoke(StateInterface $state): array;
}
