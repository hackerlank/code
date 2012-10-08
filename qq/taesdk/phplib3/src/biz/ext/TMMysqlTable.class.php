<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * Show the MysqlResult object with the HTML Table format
 *
 * @package sdk.lib3.src.biz.ext
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class TMMysqlTable {
    private $fields = array ();
    private $table = array ();

    const table = "<table cellpadding='0' cellspacing='0' border='1'>";
    const etable = "</table>";
    const tr = "<tr>";
    const etr = "</tr>";
    const td = "<td>%s</td>";
    const blank = "&nbsp;";

    /**
     * Initialize the table structure
     *
     * @param array $fields  MysqlResult object's method getAllFields returned value.
     * @param string $table  MysqlResult object's method getAllRows returned value.
     */
    public function __construct($fields, $table)
    {
        foreach ( $fields as $field )
        {
            $this->fields[] = $field->name;
        }
        $this->table = $table;
    }

    /**
     * destruct
     */
    public function __destruct()
    {
        unset ( $this->table );
        unset ( $this->fields );
    }

    /**
     * Show the entire table (with fields' header)
     *
     * @param string $tableformat  table style, default is "<table cellpadding='0' cellspacing='0' border='1'>"
     * @param string $header       fields' header style,  default is without style, you could customize it
     * @param string $trformat     tr style
     * @param string $tdformat     td style, it is for some special requirement.
     */
    public function show($tableformat = self::table, $header = self::tr, $trformat = self::tr, $tdformat = self::td)
    {
        print $tableformat;
        $this->showRow ( $this->fields, $header );
        foreach ( $this->table as $row )
        {
            $this->showRow ( $row, $trformat, $tdformat );
        }
        print self::etable;
    }

    /**
     * Show the row of table, it is for show the table with flexible style
     *
     * @param array $row  array, one row data
     * @param string $trformat  tr style
     * @param string $tdformat  td style, it is for some special requirement.
     */
    public function showRow($row, $trformat = self::tr, $tdformat = self::td)
    {
        print $trformat;
        foreach ( $row as $element )
        {
            if (empty ( $element ))
            {
                $element = self::blank;
            }
            printf ( $tdformat, $element );
        }
        print self::etr;
    }
}