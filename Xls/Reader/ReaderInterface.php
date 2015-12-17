<?php

namespace Arodiss\XlsBundle\Xls\Reader;

interface ReaderInterface
{
    /**
     * @param string $path
     * @return array
     */
    public function readAll($path);

    /**
     * @param $path
     * @param int $startRow
     * @param int $size
     * @return array
     */
    public function getByFilterAsArray($path, $startRow = 1, $size = 65000);

    /**
     * @param string $path
     * @return \Iterator
     */
    public function getReadIterator($path);

    /**
     * @param string $path
     * @return \Iterator
     */
    public function getItemsCount($path);
}
