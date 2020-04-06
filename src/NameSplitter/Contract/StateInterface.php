<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface StateInterface
 * @package NameSplitter\Contract
 */
interface StateInterface extends ResultInterface
{
    /**
     * Return parts of base string
     * @return string[]
     */
    public function getParts(): array;
    
    /**
     * Set name value
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * Set surname value
     * @param string|null $surname
     */
    public function setSurname(?string $surname): void;

    /**
     * Set middle name value
     * @param string|null $middleName
     */
    public function setMiddleName(?string $middleName): void;

    /**
     * @param string|null $initials
     */
    public function setInitials(?string $initials): void;
}