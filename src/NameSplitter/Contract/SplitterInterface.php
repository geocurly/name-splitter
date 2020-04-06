<?php

declare(strict_types=1);

namespace NameSplitter\Contract;

/**
 * Interface SplitterInterface
 * @package NameSplitter\Contract
 */
interface SplitterInterface
{
    /**
     * Split string with name parts
     *
     * @param string $name string with full name like "Blizorukov A.S." and etc.
     * @return ResultInterface
     */
    public function split(string $name): ResultInterface;
}
