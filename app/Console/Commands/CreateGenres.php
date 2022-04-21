<?php

namespace App\Console\Commands;

use App\Models\Genre;
use Illuminate\Console\Command;

class CreateGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genres:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default music genres';

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
        Genre::query()->delete();

        Genre::insert(Genre::GetDefaultGenres());

        $this->info('default genres created successfully...');

        return 0;
    }
}
