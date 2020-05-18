<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface;

/**
 * Class RussianRegex
 * @package NameSplitter\Template
 */
class RussianRegex implements TemplateInterface
{
    /** @var string[]  */
    private const TEMPLATES = [
        self::SURNAME => ['(([а-яА-ЯёЁ]+(\s*-\s*)?)+)', 3],
        self::MIDDLE_NAME => ['([а-яА-ЯёЁ]+(вич|тич|пич|лич|нич|мич|ьич|кич|вна|чна))', 2],
        self::NAME => ['([а-яА-ЯёЁ]+)', 1],
        self::INITIALS_STRICT => ['(([а-яА-ЯёЁ]\.){2})', 2],
        self::INITIALS_SPLITTED => ['([а-яА-ЯёЁ]\.\s+[а-яА-ЯёЁ]\.)', 1],
    ];

    /** @var string $regex */
    private string $regex;
    /** @var array $matchNumbers */
    private array $matchNumbers;

    /**
     * RussianRegex constructor.
     * @param array $template
     */
    public function __construct(array $template)
    {
        $regex = [];
        $match = [];

        $lastMatch = 0;
        foreach ($template as $part) {
            if (in_array($part, self::TPL_AVAILABLE, true)) {
                $matchRule = self::TEMPLATES[$part];
                $regex[] = $matchRule[0];
                // Always match first group
                $match[$part] = ($lastMatch + 1);
                // Add max group count in template
                $lastMatch += $matchRule[1];
            } else {
                $regex[] = preg_quote($part);
            }
        }

        $this->regex = '/^' . implode('\s+', $regex) . '$/iu';
        $this->matchNumbers = $match;
    }


    /**
     * @param StateInterface $state
     * @return array
     */
    public function __invoke(StateInterface $state): array
    {
        if (preg_match($this->regex, $state->getBase(), $matches, PREG_UNMATCHED_AS_NULL) !== 1) {
            return [];
        }

        $parsed = [];
        foreach ($this->matchNumbers as $name => $number) {
            $parsed[$name] = $matches[$number] ?? null;
        }

        return $parsed;
    }
}