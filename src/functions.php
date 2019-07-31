<?php

namespace Dgame\Extraction;

/**
 * @param mixed ...$fields
 *
 * @return Extractor
 */
function export(...$fields): Extractor
{
    return new Extractor($fields);
}