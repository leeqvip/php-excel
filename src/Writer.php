<?php

namespace TechOne\Excel;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use TechOne\Excel\Concerns\MapsCsvSettings;
use TechOne\Excel\Concerns\WithTitle;
use TechOne\Excel\Concerns\WithCharts;

class Writer
{
    use MapsCsvSettings;
    /**
     * @var Spreadsheet
     */
    protected $spreadsheet;
    /**
     * @var object
     */
    protected $exportable;
    /**
     * @var string
     */
    protected $tmpPath;
    /**
     * @var string
     */
    protected $file;

    public function __construct()
    {
        $this->tmpPath = config('excel.exports.temp_path', sys_get_temp_dir());
        $this->applyCsvSettings(config('excel.exports.csv', []));
        $this->setDefaultValueBinder();
    }

    public function export($export, string $writerType): string
    {
        $this->open($export);
        $sheetExports = [$export];

        foreach ($sheetExports as $sheetExport) {
            $this->addNewSheet()->export($sheetExport);
        }

        return $this->write($export, $this->tempFile(), $writerType);
    }

    /**
     * @param object $export
     *
     * @return $this
     */
    public function open($export)
    {
        $this->exportable = $export;

        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet->disconnectWorksheets();

        if ($export instanceof WithTitle) {
            $this->spreadsheet->getProperties()->setTitle($export->title());
        }

        return $this;
    }

    public function write($export, string $fileName, string $writerType)
    {
        $this->exportable = $export;

        $writer = IOFactory::createWriter($this->spreadsheet, $writerType);
        if ($export instanceof WithCharts) {
            $writer->setIncludeCharts(true);
        }
        if ($writer instanceof Csv) {
            $writer->setDelimiter($this->delimiter);
            $writer->setEnclosure($this->enclosure);
            $writer->setLineEnding($this->lineEnding);
            $writer->setUseBOM($this->useBom);
            $writer->setIncludeSeparatorLine($this->includeSeparatorLine);
            $writer->setExcelCompatibility($this->excelCompatibility);
        }

        $writer->setPreCalculateFormulas(false);
        $writer->save($fileName);
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);

        return $fileName;
    }

    public function addNewSheet(int $sheetIndex = null)
    {
        return new Sheet($this->spreadsheet->createSheet($sheetIndex));
    }

    /**
     * @param string $delimiter
     *
     * @return Writer
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @param string $enclosure
     *
     * @return Writer
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @param string $lineEnding
     *
     * @return Writer
     */
    public function setLineEnding($lineEnding)
    {
        $this->lineEnding = $lineEnding;

        return $this;
    }

    /**
     * @param bool $includeSeparatorLine
     *
     * @return Writer
     */
    public function setIncludeSeparatorLine($includeSeparatorLine)
    {
        $this->includeSeparatorLine = $includeSeparatorLine;

        return $this;
    }

    /**
     * @param bool $excelCompatibility
     *
     * @return Writer
     */
    public function setExcelCompatibility($excelCompatibility)
    {
        $this->excelCompatibility = $excelCompatibility;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultValueBinder()
    {
        Cell::setValueBinder(new DefaultValueBinder());

        return $this;
    }

    /**
     * @return string
     */
    public function tempFile()
    {
        return $this->tmpPath.DIRECTORY_SEPARATOR.'think-excel-'.mt_rand(100000, 99999999);
    }
}
