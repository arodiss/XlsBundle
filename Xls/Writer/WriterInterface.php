<?php

namespace Arodiss\XlsBundle\Xls\Writer;

interface WriterInterface
{
    /**
     * @param string $path
     * @param array $firstRow
     * @param string $sheetName
     */
    public function create($path, array $firstRow, $sheetName = null);

    /**
     * @param string $path
     * @param array $rows
     */
    public function appendRows($path, array $rows);

    /**
     * @param string $path
     * @param array $row
     */
    public function appendRow($path, array $row);

    /**
     * @param string $path
     * @param array $row
     * @param string $sheetName
     */
    public function createAndWrite($path, array $row, $sheetName = null);
}
