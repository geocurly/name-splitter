<?php

declare(strict_types=1);

namespace NameSplitter;

/**
 * Class NameSplitter
 * @package NameSplitter
 */
class NameSplitter
{
    /**
     * @inheritDoc
     */
    public function split(string $name): array
    {
        return [
            'Близоруков',
            'Александр',
            'Сергеевич',
        ];
    }
}