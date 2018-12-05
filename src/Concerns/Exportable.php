<?php

namespace TechOne\Excel\Concerns;

use TechOne\Excel\Exceptions\NoFilenameGivenException;
use TechOne\Excel\Facades\Excel;

trait Exportable
{
    public function download($fileName = null, $writerType = null)
    {
        $fileName = $fileName ? $fileName : ($this->fileName ? $this->fileName : null);
        if (null === $fileName) {
            throw new NoFilenameGivenException();
        }

        return Excel::download($this, $fileName, $writerType ? $writerType : ($this->writerType ? $this->writerType : null));
    }
}
