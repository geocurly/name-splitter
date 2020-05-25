<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface;
use RuntimeException;

/**
 * Class RussianRegex
 * @package NameSplitter\Template
 */
class RussianRegex implements TemplateInterface
{
    /** @var string[]  */
    private const TEMPLATES = [
        self::SURNAME => '([а-яА-ЯёЁ]+(\s*-\s*)?)+',
        self::MIDDLE_NAME => '[а-яА-ЯёЁ]+(вич|тич|пич|лич|нич|мич|ьич|кич|вна|чна|оглы|кызы)',
        self::NAME => '[а-яА-ЯёЁ]+',
        self::INITIALS_STRICT => '([а-яА-ЯёЁ]\.){2}',
        self::INITIALS_SPLIT => '[а-яА-ЯёЁ]\.\s+[а-яА-ЯёЁ]\.',
    ];

    /** @var string $regex */
    private string $regex;
    /** @var array $groups */
    private array $groups;

    /**
     * RussianRegex constructor.
     * @param array $template
     */
    public function __construct(array $template)
    {
        $regex = [];

        foreach ($template as $part) {
            if (in_array($part, self::TPL_AVAILABLE, true)) {
                $groupKey = mb_substr($part, 1, null, 'UTF-8');
                $matchRule = self::TEMPLATES[$part];
                $regex[$groupKey] = "(?P<$groupKey>$matchRule)";
            } else {
                throw new RuntimeException("Unavailable match key: $part");
            }
        }

        $this->regex = '/^' . implode('\s+', $regex) . '$/iu';
        $this->groups = array_keys($regex);
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
        foreach ($this->groups as $name) {
            $parsed["%$name"] = $matches[$name] ?? null;
        }

        return $parsed;
    }
}