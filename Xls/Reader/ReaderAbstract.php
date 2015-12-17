<?php

namespace Arodiss\XlsBundle\Xls\Reader;

abstract class ReaderAbstract implements ReaderInterface
{
    /** {@inheritdoc} */
    public function getHeaderRow($path)
    {
        return $this->getRowsChunk($path, 1, 1)[0];
    }

    /** {@inheirtdoc} */
    public function readAll($path)
    {
        return $this->getRowsChunk($path, 1, 1000000);
    }
}
