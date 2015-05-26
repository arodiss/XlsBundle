<?php

namespace Arodiss\XlsBundle\Xls\Builder;

use Arodiss\XlsBundle\Xls\Writer\BufferedWriter;
use Arodiss\XlsBundle\Xls\Writer\WriterInterface;

class XlsBuilder
{

    /** @var \Arodiss\XlsBundle\Xls\Writer\WriterInterface */
    protected $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param array $rows
     * @return string
     * @throws \Exception
     */
    public function buildXlsFromArray(array $rows)
    {
        if (0 === count($rows)) {
            throw new \Exception("Cannot create empty file");
        }
        $tmpFile = tempnam(sys_get_temp_dir(), "xls-build") . ".xlsx";
        $this->writer->createAndWrite($tmpFile, $rows);
        if ($this->writer instanceof BufferedWriter) {
            $this->writer->flush();
        }
        return $tmpFile;
    }
}
