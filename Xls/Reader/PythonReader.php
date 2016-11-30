<?php

namespace Arodiss\XlsBundle\Xls\Reader;

use Symfony\Component\Process\Process;

/**
 * This class is hacky, but sometimes it's the only option to achieve reasonable performance
 */
class PythonReader extends ReaderAbstract implements ReaderInterface
{
    const XLRD_SCIPT_PATH = "excel_xlrd.py";
    const OPENPYXL_SCIPT_PATH = "excel_openpyxl.py";

    const COMMAND_GET_COUNT = "count";
    const COMMAND_READ_CHUNk = "read";

    const MAX_COUNT_EMPTY_ROWS = 100;

    /** @var string */
    private $pythonExecutable;

    /** @param string $pythonExecutable */
    public function __construct($pythonExecutable = "python")
    {
        $this->pythonExecutable = $pythonExecutable;
    }

    /** {@inheirtdoc} */
    public function getRowsChunk($path, $startRow = 1, $size = 65000)
    {
        return json_decode(
            $this->executeCommand(
                self::COMMAND_READ_CHUNk,
                $path,
                "--start=$startRow --size=$size"
            )
        );
    }

    /** {@inheirtdoc} */
    public function getReadIterator($path)
    {
        return new \ArrayIterator($this->readAll($path));
    }

    /** {@inheritdoc} */
    public function getRowsNumber($path, $maxCountEmptyRows = null)
    {
        return intval($this->executeCommand(
            self::COMMAND_GET_COUNT,
            $path,
            "--max-empty-rows=" . (is_null($maxCountEmptyRows) ? self::MAX_COUNT_EMPTY_ROWS : $maxCountEmptyRows)
        ));
    }

    /**
     * @param string $commandName
     * @param string $fileName
     * @param string $extraOptions
     * @return string
     */
    protected function executeCommand($commandName, $fileName, $extraOptions = "")
    {
        $process = new Process(sprintf(
            "%s %s --action=%s --file=%s %s",
            $this->pythonExecutable,
            $this->getScriptPathForFile($fileName),
            $commandName,
            escapeshellarg($fileName),
            $extraOptions
        ));
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        return $process->mustRun()->getOutput();
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getScriptPathForFile($fileName)
    {
        $prefix = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        switch (pathinfo($fileName, PATHINFO_EXTENSION)) {
            case "xlsx":
                return $prefix . self::OPENPYXL_SCIPT_PATH;
            case "xls":
                return $prefix . self::XLRD_SCIPT_PATH;
            default:
                throw new \InvalidArgumentException(sprintf(
                    "Unsupported file extension `%s`. %s can only handle *.xls or *.xlsx",
                    pathinfo($fileName, PATHINFO_EXTENSION),
                    __CLASS__
                ));
        }
    }
}
