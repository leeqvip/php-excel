<?php

namespace TechOne\Excel;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use TechOne\Excel\Concerns\FromCollection;

class Sheet
{
    /**
     * @var int
     */
    protected $chunkSize;

    /**
     * @var string
     */
    protected $tmpPath;

    /**
     * @var object
     */
    protected $exportable;

    /**
     * @var Worksheet
     */
    private $worksheet;

    /**
     * @param Worksheet $worksheet
     */
    public function __construct(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
        $this->chunkSize = config('excel.exports.chunk_size', 100);
        $this->tmpPath = config('excel.exports.temp_path', sys_get_temp_dir());
    }

    /**
     * @param object $sheetExport
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function export($sheetExport)
    {
        // $this->open($sheetExport);
        // if ($sheetExport instanceof FromView) {
        //     $this->fromView($sheetExport);
        // } else {
        // if ($sheetExport instanceof FromQuery) {
        //     $this->fromQuery($sheetExport, $this->worksheet);
        // }
        if ($sheetExport instanceof FromCollection) {
            $this->fromCollection($sheetExport);
        }
        // if ($sheetExport instanceof FromArray) {
        //     $this->fromArray($sheetExport);
        // }
        // if ($sheetExport instanceof FromIterator) {
        //     $this->fromIterator($sheetExport);
        // }
        // }
        $this->close($sheetExport);
    }

    public function close($sheetExport)
    {
        $this->exportable = $sheetExport;
    }

    public function fromCollection(FromCollection $sheetExport)
    {
        $this->appendRows($sheetExport->collection()->all(), $sheetExport);
    }

    public function appendRows($rows, $sheetExport)
    {
        $append = [];
        foreach ($rows as $row) {
            $append[] = static::mapArraybleRow($row);
        }
        $this->append($append, null, false);
    }

    /**
     * @return bool
     */
    private function hasRows(): bool
    {
        return $this->worksheet->cellExists('A1');
    }

    public function append(array $rows, $startCell = null, $strictNullComparison = false)
    {
        if (!$startCell) {
            $startCell = 'A1';
        }
        if ($this->hasRows()) {
            $startCell = 'A'.($this->worksheet->getHighestRow() + 1);
        }
        $this->worksheet->fromArray($rows, null, $startCell, $strictNullComparison);
    }

    public static function mapArraybleRow($row)
    {
        if (method_exists($row, 'toArray')) {
            return $row->toArray();
        }

        if (is_object($row)) {
            return json_decode(json_encode($row), true);
        }

        return $row;
    }
}
