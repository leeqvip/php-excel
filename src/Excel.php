<?php

namespace TechOne\Excel;

use TechOne\Excel\Concerns\FromCollection;
use TechOne\Excel\Exceptions\NoTypeDetectedException;
use think\response\Download;

class Excel
{
    const XLSX = 'Xlsx';
    const CSV = 'Csv';
    const TSV = 'Csv';
    const ODS = 'Ods';
    const XLS = 'Xls';
    const SLK = 'Slk';
    const XML = 'Xml';
    const GNUMERIC = 'Gnumeric';
    const HTML = 'Html';
    const MPDF = 'Mpdf';
    const DOMPDF = 'Dompdf';
    const TCPDF = 'Tcpdf';

    /**
     * @var Writer
     */
    protected $writer;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    public function download(FromCollection $export, $fileName, $writerType = null)
    {
        $file = $this->export($export, $fileName, $writerType);

        return download($file, $fileName);
    }

    protected function export(FromCollection $export, $fileName, $writerType = null)
    {
        $writerType = $this->findTypeByExtension($fileName, $writerType);

        return $this->writer->export($export, $writerType);
    }

    protected function findTypeByExtension($fileName, $type = null)
    {
        if (null !== $type) {
            return $type;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (null === $type && '' === trim($extension)) {
            throw new NoTypeDetectedException();
        }

        return config('excel.extension_detector.'.strtolower($extension));
    }
}
