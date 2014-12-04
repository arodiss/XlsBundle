<?php
namespace Arodiss\XlsBundle\Xls\Writer;

interface WriterInterface
{

	/**
	 * @param string $path
	 * @param array $firstRow
	 */
	public function create($path, array $firstRow);

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
     */
    public function createAndWrite($path, array $row);
}
