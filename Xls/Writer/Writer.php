<?php
namespace Arodiss\XlsBundle\Xls\Writer;

use ExcelAnt\Adapter\PhpExcel\Sheet\Sheet;
use ExcelAnt\Adapter\PhpExcel\Workbook\Workbook;
use ExcelAnt\Adapter\PhpExcel\Writer\PhpExcelWriter\Excel5;
use ExcelAnt\Adapter\PhpExcel\Writer\WriterFactory;
use ExcelAnt\Coordinate\Coordinate;
use ExcelAnt\Table\Table;

class Writer extends AbstractWriter {

	/** {@inheritdoc} */
	public function create($path, array $firstRow) {
		$workbook = new Workbook();
		$sheet = new Sheet($workbook);
		$table = new Table();

		$table->setRow($firstRow);

		$sheet->addTable($table, new Coordinate(1, 1));
		$workbook->addSheet($sheet);

		$writer = (new WriterFactory())->createWriter(new Excel5($path));
		$phpExcel = $writer->convert($workbook);
		$writer->write($phpExcel);
	}

	/** {@inheritdoc} */
	public function appendRows($path, array $rows) {
		$phpExcel = \PHPExcel_IOFactory::load($path);
		$phpExcel->setActiveSheetIndex(0);
		$rowIndex = $phpExcel->getActiveSheet()->getHighestRow() + 1;
		foreach ($rows as $row) {
			foreach($row as $columnIndex => $value) {
				$phpExcel->getActiveSheet()->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
			}
			$rowIndex ++;
		}

		$objWriter = new \PHPExcel_Writer_Excel5($phpExcel);
		$objWriter->save($path);
	}
}
