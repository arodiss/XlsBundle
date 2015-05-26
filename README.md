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

Download XLS file
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
$response->headers->set("Content-Type", "application/vnd.ms-excel");
$response->headers->set("Content-Disposition", "attachment;filename=excelfile.xls");
$response->setContent(file_get_contents($file));
$response->send();
```


Write in a file (recommended)
--------
```PHP
$writer = $container->get("arodiss.xls.writer.buffered");
$writer->create("users.xls", array("name", "email")); //second argument represents first row

foreach ($userProvider->getUsers() as $user)
{
    $writer->appendRow("users.xls", array($user->getName(), $user->getEmail()));
}
```

Write in a file (not recommended)
--------
Write operations for xls format are extremely expensive, so previous example uses BufferedWriter which stores your data in buffer and writes in file only once, when flushing the buffer.
If for some reason this is not what you want to achieve, you may use service `xls.writer` in stead of `xls.writer.buffered`.

Formats supported
--------
Files are always written in Excel2007 (.xlsx) format. Read operations, however, MAY work also for other formats supported by PHPExcel (Excel5, Office Open XML, SpreadsheetML, OASIS, CSV, Gnumeric, SYLK) however there is no guarantee for it.
