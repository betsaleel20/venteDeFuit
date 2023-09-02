<?php

namespace Shop\shared\Infrastructure\Database;

use Illuminate\Support\Facades\DB;
use Shop\shared\Library\PdoConnexion;

class EloquentPdoConnexion implements PdoConnexion
{
    public function getPdo(): \PDO
    {
        return DB::getPdo();
    }
}
