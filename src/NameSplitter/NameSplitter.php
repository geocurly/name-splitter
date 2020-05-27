<?php

declare(strict_types=1);

namespace NameSplitter;

use NameSplitter\Contract\ResultInterface;
use NameSplitter\Contract\SplitterInterface;
use NameSplitter\Contract\StateInterface;
use NameSplitter\Contract\TemplateInterface as TPL;
use NameSplitter\Template\MiddleNameTriplet;
use NameSplitter\Template\NameDoublet;
use NameSplitter\Template\RussianRegexMatch;

/**
 * Class NameSplitter
 * @package NameSplitteradmin/export_executor.php
 */
class NameSplitter implements SplitterInterface
{
    /** @var callable[] before templates callback */
    private array $beforeTemplates;
    /** @var callable[] after templates callback */
    private array $afterTemplates;

    /**
     * NameSplitter constructor.
     * @param array $settings Setting array. See SplitterInterface constants
     * @param array $before before callbacks
     * @param array $after after callbacks
     */
    public function __construct(array $settings = [], array $before = [], array $after = [])
    {
        Settings::setEncoding($settings[self::SETTING_ENCODING] ?? 'UTF-8');
        $this->beforeTemplates = $before;
        $this->afterTemplates = $after;
    }

    /**
     * Callable templates
     * @return callable[]
     */
    private function getDefaultTemplates(): array
    {
        return [
            new RussianRegexMatch([TPL::SURNAME, TPL::INITIALS_STRICT]), // Иванов И.И.
            new RussianRegexMatch([TPL::INITIALS_STRICT, TPL::SURNAME]), // И.И. Иванов
            new MiddleNameTriplet(), // Dictionary middle-name check
            new NameDoublet(), // Иванов Иван or Иван Иванов
            new RussianRegexMatch([TPL::INITIALS_SPLIT, TPL::SURNAME]), // И. И. Иванов
            new RussianRegexMatch([TPL::SURNAME, TPL::INITIALS_SPLIT]), // Иванов И. И.
            new RussianRegexMatch([TPL::SURNAME, TPL::NAME, TPL::MIDDLE_NAME]), // Иванов Иван Иванович
            new RussianRegexMatch([TPL::NAME, TPL::MIDDLE_NAME, TPL::SURNAME]), // Иван Иванович Иванов
            new RussianRegexMatch([TPL::NAME, TPL::MIDDLE_NAME]), // Иван Иванович
        ];
    }

    /**
     * @param StateInterface $state
     * @param array $matches
     * @return ResultInterface
     */
    private function fillState(StateInterface $state, array $matches): ResultInterface
    {
        foreach (array_filter($matches, fn($match) => $match !== null) as $part => $value) {
            if (Settings::getEncoding() !== 'UTF-8') {
                $value = mb_convert_encoding($value, Settings::getEncoding(), 'UTF-8');
            }

            switch ($part) {
                case TPL::SURNAME:
                    $state->setSurname($value);
                    break;
                case TPL::NAME:
                    $state->setName($value);
                    break;
                case TPL::MIDDLE_NAME:
                    $state->setMiddleName($value);
                    break;
                case TPL::INITIALS_STRICT:
                case TPL::INITIALS_SPLIT:
                    $state->setInitials($value);
                    break;
                default:
                    throw new \RuntimeException("Unknown match type: $part");
            }
        }

        return $state;
    }

    /**
     * @inheritDoc
     */
    public function split(string $name): ResultInterface
    {
        if (Settings::getEncoding() !== 'UTF-8') {
            $name = mb_convert_encoding($name, 'UTF-8', Settings::getEncoding());
        }

        $templates = [...$this->beforeTemplates, ...$this->getDefaultTemplates(), ...$this->afterTemplates];
        $state = new SplitState($name);
        foreach ($templates as $template) {
            $match = $template($state);
            if ($match !== []) {
                return $this->fillState($state, $match);
            }
        }

        return $state;
    }
}