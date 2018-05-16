<?php

namespace Dgame\Extraction;

use function Dgame\Ensurance\enforce;

/**
 * Class Extractor
 * @package Dgame\Extraction
 */
final class Extractor
{
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $defaults = [];
    /**
     * @var array
     */
    private $required = [];

    /**
     * Extractor constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param array $defaults
     *
     * @return Extractor
     */
    public function defaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @param string $field
     *
     * @return mixed|null
     */
    private function getDefaultOf(string $field)
    {
        return array_key_exists($field, $this->defaults) ? $this->defaults[$field] : null;
    }

    /**
     * @return Extractor
     */
    public function requireAll(): self
    {
        return $this->require(...$this->fields);
    }

    /**
     * @param string ...$fields
     *
     * @return Extractor
     */
    public function require(string ...$fields): self
    {
        $messages = [];
        foreach ($fields as $field) {
            $messages[$field] = null;
        }

        return $this->orFailWith($messages);
    }

    /**
     * @param array $messages
     *
     * @return Extractor
     */
    public function orFailWith(array $messages): self
    {
        foreach ($messages as $field => $message) {
            $this->required[$field] = $message;
        }

        return $this;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function isRequired(string $field): bool
    {
        return array_key_exists($field, $this->required);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    private function getExceptionMessage(string $field): string
    {
        return $this->required[$field] ?? 'Field "%s" is required';
    }

    /**
     * @param array $source
     *
     * @return array
     */
    public function from(array $source): array
    {
        $output = [];
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $source)) {
                enforce(!$this->isRequired($field))->orThrow($this->getExceptionMessage($field), $field);
                $value = $this->getDefaultOf($field);
            } else {
                $value = $source[$field];
            }

            $output[$field] = $value;
        }

        return $output;
    }
}
