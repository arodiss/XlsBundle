<?php
namespace Arodiss\XlsBundle\Xls\Writer;

class BufferedWriter extends AbstractWriter
{

    /** @var WriterInterface */
    protected $writer;

    /** @var array */
    protected $buffers = array();

    /** @param WriterInterface $writer */
    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function __destruct()
    {
        $this->flush();
    }

    /** {@inheritdoc} */
    public function create($path, array $firstRow, $sheetName = null)
    {
        return $this->writer->create($path, $firstRow, $sheetName);
    }

    /** {@inheritdoc} */
    public function appendRows($path, array $rows)
    {
        $this->createBuffer($path);
        foreach ($rows as $row) {
            array_push($this->buffers[$path], $row);
        }
    }

    /** @param null|string $bufferName */
    public function flush($bufferName = null)
    {
        foreach ($this->getBuffers($bufferName) as $path => $buffer){
            $this->writer->appendRows($path, $buffer);
            $this->removeBuffer($path);
        }
    }

    /** @param null|string $bufferName */
    public function discard($bufferName = null)
    {
        foreach ($this->getBuffers($bufferName) as $path => $buffer) {
            $this->removeBuffer($path);
        }
    }

    /** {@inheritdoc} */
    public function createAndWrite($path, array $rows, $sheetName = null)
    {
        $this->writer->createAndWrite($path, $rows, $sheetName);
    }

    /** @param string $path */
    protected function createBuffer($path)
    {
        if (false == isset($this->buffers[$path])) {
            $this->buffers[$path] = array();
        }
    }

    /** @param string $path */
    protected function removeBuffer($path)
    {
        unset($this->buffers[$path]);
    }

    /**
     * @param null|string $bufferName
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function getBuffers($bufferName = null)
    {
        if ($bufferName) {
            if (false == isset($this->buffers[$bufferName])) {
                throw new \InvalidArgumentException("There is no buffer for $bufferName");
            }
            return array($bufferName => $this->buffers[$bufferName]);
        } else {
            return $this->buffers;
        }
    }
}
