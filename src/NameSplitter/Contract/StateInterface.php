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
     * @param array $parts
     * @param array $counts
     */
    public function setParts(array $parts, array $counts): void;

    /**
     * Return length of name parts
     * @param int $num
     * @return int|null
     */
    public function getLengthPart(int $num): ?int;
    
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

    /**
     * Set gender value
     * @see ResultInterface const
     * @param int|null $gender
     */
    public function setGender(?int $gender): void;
}