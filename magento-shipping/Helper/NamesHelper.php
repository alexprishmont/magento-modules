<?php
declare(strict_types=1);

namespace Alexpr\SimpleShipping\Helper;

class NamesHelper
{
    private $map = null;

    public function __construct(Config $config)
    {
        $this->map = $config->getMap();
    }

    public function normalizeName(string $name): string
    {
        $result = str_replace('_', ' ', $name);

        if ($this->map === null) {
            return ucwords(strtolower($result));
        }

        if ($fromMap = $this->checkInMap($result)) {
            return ucwords(strtolower((string)$fromMap));
        }

        return ucwords(strtolower($result));
    }

    private function checkInMap(string $needle)
    {
        foreach ($this->map as $item) {
            if (array_search($needle, $item)) {
                return $item['custom_name'];
            }
        }
        return false;
    }
}