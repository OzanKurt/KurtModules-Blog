<?php

namespace Kurt\Modules\Blog\Console\Commands;

use Illuminate\Console\Command;
use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Traits\GetUserModelData;

class SeedCommand extends Command
{
    use GetUserModelData;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kurtmodules-blog:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with random data.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->getUserModel()->count() == 0)
        {
            $this->error("There is no user in the database, cannot seed.");
        }
        
        factory(Category::class)->times(5)->create();
        
        factory(Post::class)->times(5)->create();
        
        $this->info('Done.');
    }
}
