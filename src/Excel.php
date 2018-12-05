<?php

namespace TechOne\Excel;

use TechOne\Excel\Exceptions\NoTypeDetectedException;

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

    public $config = [];

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    public function setWriter(Writer $writer)
    {
        $this->writer = $writer;
    }

    public static function init($config = [])
    {
        return new Excel(new Writer());
    }

    public function download($export, $fileName, $writerType = null)
    {
        $filePath = $this->export($export, $fileName, $writerType);

        ob_clean();

        $fp = fopen($filePath, 'r');
        $fileSize = filesize($filePath);

        header('Content-type:application/octet-stream');
        header('Accept-Ranges:bytes');
        header('Accept-Length:'.$fileSize);
        header('Content-Disposition: attachment; filename='.$fileName);

        $buffer = 1024;
        $bufferCount = 0;
        while (!feof($fp) && $fileSize - $bufferCount > 0) {
            $data = fread($fp, $buffer);
            $bufferCount += $buffer;
            echo $data;
        }
        fclose($fp);
    }

    protected function export($export, $fileName, $writerType = null)
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

        return Settings::get('extension_detector.'.strtolower($extension));
    }
}
