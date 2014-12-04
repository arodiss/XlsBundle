<?php
namespace Arodiss\XlsBundle\Xls\Reader;

use Arodiss\XlsBundle\Iterator\NestingDiscloseIterator;
use Arodiss\XlsBundle\Iterator\StringifyIterator;
use Arodiss\XlsBundle\Filter\ReadFilter;
use Arodiss\XlsBundle\Filter\RowFilter;

class Reader
{

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
        return RowFilter::clearEmptyRows($this->getPartOfExcel($path, $startRow, $size)->toArray());
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
        $excel = $this->getExcelWithoutSheet($path)->load($path);

        return $excel->getActiveSheet();
    }

    /**
     * @param $path
     * @param $startRow
     * @param $size
     * @return \PHPExcel_Worksheet
     */
    protected function getPartOfExcel($path, $startRow, $size)
    {
        $reader = $this->getExcelWithoutSheet($path);
        $reader->setReadFilter(new ReadFilter($startRow, $size));
        /** @var \PHPExcel $excel */
        $excel = $reader->load($path);

        return $excel->getActiveSheet();
    }

    /**
     * @param $path
     * @return \PHPExcel_Reader_Abstract
     * @throws \PHPExcel_Reader_Exception
     */
    protected function getExcelWithoutSheet($path)
    {
        $reader = $this->createReaderForFile($path);
        $reader->setReadDataOnly(true);

        return $reader;
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
