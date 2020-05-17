<?php

declare(strict_types=1);

namespace NameSplitter;

use NameSplitter\Contract\StateInterface;

/**
 * Class SplitState
 * @package NameSplitter
 */
class SplitState implements StateInterface
{
    /** @var string $base - base name string */
    private string $base;

    /** @var array $parts */
    private array $parts;
    /** @var int[] */
    private array $lengthMap;

    /** @var string|null $surname */
    private ?string $surname = null;
    /** @var string|null $middleName */
    private ?string $middleName = null;
    /** @var string|null $name */
    private ?string $name = null;
    /** @var string|null $initials */
    private ?string $initials = null;
    /** @var int|null $gender */
    private ?int $gender = null;

    /**
     * SplitProcess constructor.
     * @param string $base
     */
    public function __construct(string $base)
    {
        $this->base = $base;
        $this->parts = preg_split(
            '/\s+/',
            // '-' excludes for checking double surname
            preg_replace('/\s+(?=[-.])|(?<=[-])\s+/', '', $base)
        );

        $mapArgs = array_fill(0, count($this->parts), 'utf8');
        $this->lengthMap = array_map('mb_strlen', $this->parts, $mapArgs);
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @param string|null $middleName
     */
    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @param string|null $initials
     */
    public function setInitials(?string $initials): void
    {
        $this->initials = mb_strtoupper($initials, 'utf8');
    }

    /**
     * @inheritDoc
     */
    public function getInitials(): ?string
    {
        return $this->initials;
    }

    /**
     * @return int|null
     */
    public function getGender(): ?int
    {
        return $this->gender;
    }

    /**
     * @param int|null $gender
     */
    public function setGender(?int $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @param int $num
     * @return int|null
     */
    public function getLengthPart(int $num): ?int
    {
        return $this->lengthMap[$num] ?? null;
    }

    /**
     * @param array $parts
     * @param array $counts
     */
    public function setParts(array $parts, array $counts): void
    {
        $this->parts = $parts;
        $this->lengthMap = $counts;
    }
}
