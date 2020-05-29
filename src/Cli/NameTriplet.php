<?php

declare(strict_types=1);

namespace Cli;

/**
 * Class NameTriplet
 * @package Cli
 */
class NameTriplet
{
    public ?string $surname;
    public ?string $name;
    public ?string $middle;

    /**
     * NameObject constructor.
     * @param string $surname
     * @param string $name
     * @param string $middle
     */
    public function __construct(string $surname, string $name, string $middle)
    {
        $this->surname = trim($surname) ?: null;
        $this->name = trim($name) ?: null;
        $this->middle = trim($middle) ?: null;
    }
}
