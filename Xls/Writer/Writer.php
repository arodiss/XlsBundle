<?php
namespace Arodiss\XlsBundle\Xls\Writer;

use \PHPExcel_IOFactory;
use \PHPExcel_Writer_Excel2007;
use \PHPExcel_Cell_DataType;
use \PHPExcel;

class Writer extends AbstractWriter
{
    /** {@inheritdoc} */
    public function appendRows($path, array $rows)
    {
        $phpExcel = PHPExcel_IOFactory::load($path);
        $phpExcel->setActiveSheetIndex(0);
        $rowIndex = $phpExcel->getActiveSheet()->getHighestRow() + 1;
        foreach ($rows as $row) {
            foreach($row as $columnIndex => $value) {
                $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
            }
            $rowIndex ++;
        }

        $this->createWriter($phpExcel)->save($path);
    }

    /**
     * @param string $path
     * @param array $rows
     */
    public function createAndWrite($path, array $rows)
    {
        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);
        $maxColumnIndex = 0;

        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $columnIndex => $cell) {
                $maxColumnIndex = max($columnIndex, $maxColumnIndex);

                $phpExcel
                    ->getActiveSheet()
                    ->setCellValueExplicitByColumnAndRow(
                        $columnIndex,
                        $rowIndex + 1,
                        $cell,
                        PHPExcel_Cell_DataType::TYPE_STRING
                    )
                ;
            }
        }

        for ($i = 0; $i<$maxColumnIndex; $i++) {
            $phpExcel
                ->getActiveSheet()
                ->getColumnDimensionByColumn($i)
                ->setAutoSize(true)
            ;
        }

        $this->createWriter($phpExcel)->save($path);
    }

    /**
     * @param PHPExcel $phpExcel
     * @return PHPExcel_Writer_Excel2007
     */
    protected function createWriter(PHPExcel $phpExcel)
    {
        return new PHPExcel_Writer_Excel2007($phpExcel);
    }
}
