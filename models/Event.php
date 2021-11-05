<?php
class Event {
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

    public function deposit($input) {
        return [
            'status' => '200',
            'data' => $input,
        ];
    }

    public function withdraw($input) {
        return [
            'status' => '200',
            'data' => $input,
        ];
    }

    public function transfer($input) {
        return [
            'status' => '200',
            'data' => $input,
        ];
    }
}