<?php

namespace App\Services;

use Illuminate\Database\DatabaseManager;

class VendorService
{
    private $db;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->db = $databaseManager->connection();
    }

    public function exists(int $id) : bool
    {
        $exists = false;
        $results = $this->db->select('select id from ppl_vendors where id = :vendorId', ['vendorId' => $id]);

        if (count($results) > 0) {
            $exists = true;
        }

        return $exists;
    }
}