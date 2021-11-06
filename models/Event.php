<?php
class Event {
    private $accounts = [];

    public function __construct() {
        $this->read_csv_file();
    }

    private function read_csv_file() {
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

    private function write_csv_file() {
        $csv_file = dirname(__FILE__) . '/../accounts.csv';

        if (file_exists($csv_file)) {
            unlink($csv_file);
        }

        $f = fopen($csv_file, 'w');
        if ($f === false) {
            die('Error opening the file ' . $csv_file);
        }
        foreach ($this->accounts as $row) {
            fputcsv($f, $row);
        }
        fclose($f);
    }

    public function deposit($input) {
        if (!isset($input->destination) || !is_numeric($input->destination)) {
            return [
                'status' => '404',
                'data' => 'Destination not found',
            ];
        }
        if (!isset($input->amount) || !is_numeric($input->amount) || $input->amount < 0) {
            return [
                'status' => '400',
                'data' => 'Amount not valid'
            ];
        }

        $account_id         = $input->destination;
        $account_ids_column = array_column($this->accounts, 0);
        $account_index      = array_search($account_id, $account_ids_column);

        if ($account_index === false) {
            $this->accounts[] = [$input->destination, $input->amount];
            $this->write_csv_file();
            return [
                'status' => '201',
                'data'   => [
                    'destination' => [
                        'id'      => $input->destination,
                        'balance' => $input->amount
                    ]
                ] ,
            ];
        } else {
            $this->accounts[$account_index][1] = (float)$this->accounts[$account_index][1] + (float)$input->amount;

            $this->write_csv_file();

            return [
                'status' => '201',
                'data'   => [
                    'destination' => [
                        'id'      => $this->accounts[$account_index][0],
                        'balance' => $this->accounts[$account_index][1]
                    ]
                ] ,
            ];
        }
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