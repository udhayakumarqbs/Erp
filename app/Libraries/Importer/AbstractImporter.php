<?php

namespace App\Libraries\Importer;

//defined('BASEPATH') or exit('No direct script access allowed');

interface AbstractImporter
{
    //format
    const CSV = 1;

    //table
    const LEAD = 2;
    const CUSTOMER = 3;
    const RAWMATERIAL = 4;
    const SEMIFINISHED = 5;
    const FINISHEDGOOD = 6;
    const GLACCOUNT = 7;
    const SUPPLIER = 8;
    const EMPLOYEE = 9;
    const CONTRACTOR = 10;

    public function get_columns();

    /**
     * returns
     * 1 Full Import
     * 0 Partial Import
     * -1 No Import
     */
    public function import($filepath, $common_columns = array());
    public function download_template();
}
