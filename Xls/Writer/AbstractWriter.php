<?php
namespace Arodiss\XlsBundle\Xls\Writer;

use ExcelAnt\Adapter\PhpExcel\Sheet\Sheet;
use ExcelAnt\Adapter\PhpExcel\Workbook\Workbook;
use ExcelAnt\Adapter\PhpExcel\Writer\PhpExcelWriter\Excel5;
use ExcelAnt\Adapter\PhpExcel\Writer\WriterFactory;
use ExcelAnt\Coordinate\Coordinate;
use ExcelAnt\Table\Table;

abstract class AbstractWriter implements WriterInterface {
	/** {@inheritdoc} */
	public function appendRow($path, array $row) {
		$this->appendRows($path, array($row));
	}
}
