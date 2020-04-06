<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface ResultInterface
 * @package NameSplitter\Contract
 */
interface ResultInterface
{
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
}
