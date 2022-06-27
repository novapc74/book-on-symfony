<?php

namespace App\Utils\CategoryMaker;

class Normalizer
{
    /**
     * @var array $replaceData
     */
    private array $replaceData = [
        "with",
        "and",
        "And",
        "Editors",
        "With",
        "with",
        "contributions",
        "by",
        "writing",
        "as",
        "Forewordby",
        "Edited",
        "Contributions from 53 SQL Server MVPs",
        "friends",
        "Compiled",
        "introduced",
        "editors",
        "/",
    ];

    /**
     * @param  string $data
     * @return array
     */
    public function normalize(array $collection): array
    {
        $mapping = array_map(fn($item) => trim(ucfirst(str_replace($this->getReplaceData(), '', $item)), " .;,"), $collection);
        return array_filter($mapping, fn($item) => $item != null);
    }

    /**
     * @return array
     */
    private function getReplaceData(): array
    {
        return $this->replaceData;
    }
}