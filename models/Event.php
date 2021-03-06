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
        if (!isset($input->origin) || !is_numeric($input->origin)) {
            return [
                'status' => '404',
                'data'   => 'Origin not found',
            ];
        }
        if (!isset($input->amount) || !is_numeric($input->amount) || $input->amount < 0) {
            return [
                'status' => '400',
                'data'   => 'Amount not valid'
            ];
        }

        $account_id         = $input->origin;
        $account_ids_column = array_column($this->accounts, 0);
        $account_index      = array_search($account_id, $account_ids_column);

        if ($account_index === false) {
            return [
                'status' => '404',
                'data'   => 0,
            ];
        } elseif ((float)$input->amount > (float)$this->accounts[$account_index][1]) {
            return [
                'status' => '405',
                'data'   => 'Insuficient funds',
            ];
        } else {
            $this->accounts[$account_index][1] = (float)$this->accounts[$account_index][1] - (float)$input->amount;

            $this->write_csv_file();

            return [
                'status' => '201',
                'data'   => [
                    'origin' => [
                        'id'      => $this->accounts[$account_index][0],
                        'balance' => $this->accounts[$account_index][1]
                    ]
                ] ,
            ];
        }
    }

    public function transfer($input) {
        if (!isset($input->destination) || !is_numeric($input->destination)) {
            return [
                'status' => '404',
                'data' => 'Destination not found',
            ];
        }
        if (!isset($input->origin) || !is_numeric($input->origin)) {
            return [
                'status' => '404',
                'data'   => 'Origin not found',
            ];
        }
        if (!isset($input->amount) || !is_numeric($input->amount) || $input->amount < 0) {
            return [
                'status' => '400',
                'data'   => 'Amount not valid'
            ];
        }

        $origin_account_id         = $input->origin;
        $origin_account_ids_column = array_column($this->accounts, 0);
        $origin_account_index      = array_search($origin_account_id, $origin_account_ids_column);
        if ($origin_account_index === false) {
            return [
                'status' => '404',
                'data'   => 0,
            ];
        } elseif ((float)$input->amount > (float)$this->accounts[$origin_account_index][1]) {
            var_dump((float)$input->amount);
            var_dump((float)$this->accounts[$origin_account_index][1]);
            return [
                'status' => '405',
                'data'   => 'Insuficient funds',
            ];
        }

        $destination_account_id         = $input->destination;
        $destination_account_ids_column = array_column($this->accounts, 0);
        $destination_account_index      = array_search($destination_account_id, $destination_account_ids_column);
        if ($destination_account_index === false) {
            $this->accounts[] = [$destination_account_id, 0];
            $destination_account_index = count($this->accounts) - 1;

        }

        $this->accounts[$origin_account_index][1] = (float)$this->accounts[$origin_account_index][1] - (float)$input->amount;
        $this->accounts[$destination_account_index][1] = (float)$this->accounts[$destination_account_index][1] + (float)$input->amount;

        $this->write_csv_file();

        return [
            'status' => '201',
            'data'   => [
                'origin' => [
                    'id'      => $this->accounts[$origin_account_index][0],
                    'balance' => $this->accounts[$origin_account_index][1]
                ],
                'destination' => [
                    'id'      => $this->accounts[$destination_account_index][0],
                    'balance' => $this->accounts[$destination_account_index][1]
                ]
            ] ,
        ];
    }
}