<?php

namespace Arodiss\XlsBundle\Filter;

class RowFilter
{

    /**
     * @param array $rows
     * @return array
     */
    public static function clearEmptyRows(array $rows)
    {
        foreach ($rows as $key => $row) {
            $row = array_filter($row);
            if (empty($row)) {
                unset($rows[$key]);
            }
        }

        return $rows;
    }
}
