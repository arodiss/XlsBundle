<?php
namespace Arodiss\XlsBundle\Xls\Writer;

abstract class AbstractWriter implements WriterInterface
{
    /** {@inheritdoc} */
    public function create($path, array $firstRow)
    {
        $this->createAndWrite($path, [$firstRow]);
    }

	/** {@inheritdoc} */
	public function appendRow($path, array $row)
    {
		$this->appendRows($path, array($row));
	}
}
