<?php

namespace Arodiss\XlsBundle\Filter;

class ReadFilter implements \PHPExcel_Reader_IReadFilter
{

    /** @var int */
    private $size;

    /** @var  int */
    private $startRow;

    /** @var  int */
    private $maxRow;

    /**
     * @param int $startRow
     * @param int $size
     */
    public function __construct($startRow, $size)
    {
        $this->startRow = $startRow;
        $this->size = $size;
        $this->maxRow = ($this->startRow + $this->size) - 1;
    }

    /** {@inheritdoc} */
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->maxRow) {
            return true;
        }

        return false;
    }
}
