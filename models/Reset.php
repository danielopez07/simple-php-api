<?php
class Reset {
    public function __construct() {
        //
    }

    public function reset_csv() {
        $csv_file = dirname(__FILE__) . '/../accounts.csv';

        if (file_exists($csv_file)) {
            unlink($csv_file);
        }

        $data = [ ['account', 'amount'] ];
        $f = fopen($csv_file, 'w');
        if ($f === false) {
            die('Error opening the file ' . $csv_file);
        }
        foreach ($data as $row) {
            fputcsv($f, $row);
        }
        fclose($f);

        return 'OK';
    }
}