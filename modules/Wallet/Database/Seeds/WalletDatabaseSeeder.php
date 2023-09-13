<?php

namespace Modules\Wallet\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class WalletDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        Model::reguard();
    }
}
