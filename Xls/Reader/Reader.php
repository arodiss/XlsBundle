<?php
namespace Arodiss\XlsBundle\Xls\Reader;

use Arodiss\XlsBundle\Iterator\NestingDiscloseIterator;
use Arodiss\XlsBundle\Iterator\StringifyIterator;

class Reader {

	/**
	 * @param string $path
	 * @return array
	 */
	public function readAll($path) {
		return $this->getExcel($path)->toArray();
	}

	/**
	 * @param string $path
	 * @return \Iterator
	 */
	public function getReadIterator($path) {
		return new StringifyIterator(new NestingDiscloseIterator($this->getExcel($path)->getRowIterator()));
	}

	/**
	 * @param string $path
	 * @return \Iterator
	 */
	public function getItemsCount($path) {
		return $this->getExcel($path)->getHighestRow();
	}

	/**
	 * @param string $path
	 * @return \PHPExcel_Worksheet
	 */
	protected function getExcel($path) {
		$reader = \PHPExcel_IOFactory::createReaderForFile($path);
		/** @var \PHPExcel $excel */
		$excel = $reader->load($path);
		return $excel->getActiveSheet();
	}
}
