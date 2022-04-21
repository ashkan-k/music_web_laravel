<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CreateSuperuser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superuser:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default superuser';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::create(
            [
                'first_name' => 'اشکان',
                'last_name' => 'کریمی',
                'email' => 'as@gmail.com',
                'phone' => '09396988720',
                'password' => Hash::make('123'),
                'email_verified_at' => Carbon::now(),
                'is_superuser' => true,
            ]
        );

        $this->info('09396988720 superuser created...');


        return 0;
    }
}
