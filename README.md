Xls Bundle
==========

Trivia read and write of .xls files for Symfony2, built on top of the [PHPExcel](https://github.com/PHPOffice/PHPExcel/)

Installation
--------
Via composer:
```
require: {
    ...
    "arodiss/xls-bundle": "*@stable"
}
```

Usage examples:
--------

Read a small file
--------
```PHP
$reader = $container->get("arodiss.xls.reader");
$content = $reader->readAll("/path/to/file.xls");
var_dump($content);
//array(2) =>
//    array(2) =>
//        0 => string(10) "First line"
//        1 => null
//    array(2) =>
//        0 => string(10) "Line number"
//        1 => int 2
```

Read a big file
--------
Previous method can exhaust your memory if the file is big. However this is safe:
```PHP
$reader = $container->get("arodiss.xls.reader");
$iterator = $reader->getReadIterator("/path/to/file.xls");
while($iterator->valid())
{
    var_dump($iterator->current());
    $iterator->next();
}
//same output format
```

Read even a bigger file
--------
Sometime PHPOffice just can't provide reasonable performance. For this case bundle provides alternative reader which wraps python implementation.
It is rudimentary in terms of functionality and especially interactions (like error handling), but performs faster, especially on large files.
In order to use it, you have to install `openpyxl` (for xlsx) and xlrd (for xls) libraries, which you can easily do through [pip](https://pypi.python.org/pypi/pip) package manager

```PHP
$reader = $container->get("arodiss.xls.reader.python");
$iterator = $reader->getReadIterator("/path/to/file.xls");
while($iterator->valid())
{
    var_dump($iterator->current());
    $iterator->next();
}
//same output format
```

Return XLS file from Symfony controller
--------
```PHP
$file = $container
    ->get("arodiss.xls.builder")
    ->createAndWrite(array(
        array("row one field one", "row one field two"),
        array("row two field one")
    ))
;

//now $file is path to tmp file with data

$response = new Response();
$response->headers->set("Content-Type", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
$response->headers->set("Content-Disposition", "attachment;filename=excelfile.xlsx");
$response->setContent(file_get_contents($file));
return $response;
```


Write in a file (recommended)
--------
```PHP
$writer = $container->get("arodiss.xls.writer.buffered");
$writer->create("users.xls", array("name", "email")); //second argument represents first row

foreach ($userProvider->getUsers() as $user) {
    $writer->appendRow("users.xls", array($user->getName(), $user->getEmail()));
}
$writer->flush();

```

Write in a file (not recommended)
--------
Write operations for xls format are extremely expensive, so previous example uses BufferedWriter which stores your data in buffer and writes in file only once, when flushing the buffer.
If for some reason this is not what you want to achieve, you may use service `xls.writer` in stead of `xls.writer.buffered`.

Formats supported
--------
Files are always written in Excel2007 (.xlsx) format. Read operations, however, MAY work also for other formats supported by PHPExcel (Excel5, Office Open XML, SpreadsheetML, OASIS, CSV, Gnumeric, SYLK) however there is no guarantee for it.
