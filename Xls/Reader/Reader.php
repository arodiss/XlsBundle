<?php
namespace Arodiss\XlsBundle\Xls\Reader;

use Arodiss\XlsBundle\Iterator\NestingDiscloseIterator;
use Arodiss\XlsBundle\Iterator\StringifyIterator;
use Arodiss\XlsBundle\Filter\ReadFilter;
use Arodiss\XlsBundle\Filter\RowFilter;

class Reader
{
    /** \PHPExcel_Reader_Abstract[] */
    private $worksheets = [];

    /**
     * @param string $path
     * @return array
     */
    public function readAll($path)
    {
        return $this->getExcel($path)->toArray();
    }

    /**
     * @param $path
     * @param int $startRow
     * @param int $size
     * @return array
     */
    public function getByFilterAsArray($path, $startRow = 1, $size = 65000)
    {
        return RowFilter::clearEmptyRows($this->getWorksheetPart($path, $startRow, $size)->toArray());
    }

    /**
     * @param string $path
     * @return \Iterator
     */
    public function getReadIterator($path)
    {
        return new StringifyIterator(new NestingDiscloseIterator($this->getExcel($path)->getRowIterator()));
    }

    /**
     * @param string $path
     * @return \Iterator
     */
    public function getItemsCount($path)
    {
        return $this->getExcel($path)->getHighestRow();
    }

    /**
     * @param string $path
     * @return \PHPExcel_Worksheet
     */
    protected function getExcel($path)
    {
        /** @var \PHPExcel $excel */
        return $this->getWorksheet($path);
    }

    /**
     * @param $path
     * @param $startRow
     * @param $size
     * @return \PHPExcel_Worksheet
     */
    protected function getWorksheetPart($path, $startRow, $size)
    {
        return $this->getWorksheet($path, new ReadFilter($startRow, $size));
    }

    /**
     * @param string $path
     * @param \PHPExcel_Reader_IReadFilter $readFilter
     * @param $path
     * @return \PHPExcel_Worksheet
     * @throws \PHPExcel_Reader_Exception
     */
    protected function getWorksheet($path, \PHPExcel_Reader_IReadFilter $readFilter = null)
    {
        if (false === isset($this->worksheets[$path]) || $readFilter) {
            $reader = $this->createReaderForFile($path);
            $reader->setReadDataOnly(true);
            if ($readFilter) {
                $reader->setReadFilter($readFilter);
            }
            $this->worksheets[$path] = $reader->load($path)->getActiveSheet();
        }

        return $this->worksheets[$path];
    }

    /**
     * @param string $path
     * @return \PHPExcel_Reader_Abstract
     */
    protected function createReaderForFile($path)
    {
        return \PHPExcel_IOFactory::createReaderForFile($path);
    }
}
