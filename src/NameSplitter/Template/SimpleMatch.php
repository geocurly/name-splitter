<?php

declare(strict_types=1);

namespace NameSplitter\Template;

use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface;

/**
 * Class SimpleMatch
 * @package NameSplitter\Template
 */
class SimpleMatch implements TemplateInterface
{
    /** @var array matching map */
    private array $matching;

    /**
     * SimpleMatch constructor.
     * @param array $matching
     */
    public function __construct(array $matching)
    {
        foreach ($matching as $key => $match) {
            if (!in_array($key, self::TPL_AVAILABLE, true)) {
                throw new \RuntimeException("Unavailable match key: $key");
            }

            $groupKey = mb_substr($key, 1, null, 'UTF-8');
            $match = preg_quote($match);
            $this->matching[$groupKey] = "(?P<$groupKey>$match)";
        }
    }

    /**
     * @param StateInterface $state
     * @return array
     */
    public function __invoke(StateInterface $state): array
    {
        $pattern = implode('\s+', $this->matching);
        if (preg_match("/^$pattern$/u", $state->getBase(), $match) !== 1) {
            return [];
        }

        $result = [];
        foreach ($this->matching as $keyGroup => $_) {
            $result["%$keyGroup"] = $match[$keyGroup] ?? null;
        }

        return $result;
    }
}
