<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface ResultInterface
 * @package NameSplitter\Contract
 */
interface ResultInterface
{
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    /** There is could be another else:) */

    /**
     * Get result name
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get result middle name
     * @return string|null
     */
    public function getMiddleName(): ?string;

    /**
     * Get result surname
     * @return string|null
     */
    public function getSurname(): ?string;

    /**
     * Get result initials
     * @return string|null
     */
    public function getInitials(): ?string;

    /**
     * Get parsed gender
     * @return int|null
     */
    public function getGender(): ?int;
}
