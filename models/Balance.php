<?php
class Balance {
    private $accounts = [];

    public function __construct() {
        $this->read_csv_file();
    }

    public function read_csv_file() {
        // read accounts cache from CSV file
        $csv_file = dirname(__FILE__) . '/../accounts.csv';
        $f        = fopen($csv_file, 'r');

        if ($f === false) {
            die('Cannot open the file ' . $csv_file);
        }

        while (($row = fgetcsv($f)) !== false) {
            $this->accounts[] = $row;
        }
        fclose($f);
    }

    public function read($account_id) {
        $account_ids_column = array_column($this->accounts, 0);
        $account_index      = array_search($account_id, $account_ids_column);
        $amount_in_account  = $account_index !== false ? $this->accounts[$account_index][1] : false;
        
        return $amount_in_account;
    }
}