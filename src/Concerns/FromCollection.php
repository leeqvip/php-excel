<?php

namespace TechOne\Excel\Concerns;

use think\Collection;

interface FromCollection
{
    /**
     * @return Collection
     */
    public function collection();
}
