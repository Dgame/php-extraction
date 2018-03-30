<?php

namespace Dgame\Extraction;

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
     * @param array $source
     *
     * @return array
     */
    public function from(array $source): array
    {
        $output = [];
        foreach ($this->fields as $field) {
            $output[$field] = array_key_exists($field, $source) ? $source[$field] : $this->getDefaultOf($field);
        }

        return $output;
    }
}