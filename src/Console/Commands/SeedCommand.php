<?php

namespace Kurt\Modules\Blog\Console\Commands;

use Illuminate\Console\Command;

use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Models\Post;

use Kurt\Modules\Core\Traits\GetUserModelData;

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
     * Default configuration for seeding.
     * 
     * @var array
     */
    protected $settings = [
        'categoryCount' => 5,
        'postCount'     => 25,
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->getUserModel()->count() == 0)
        {
            $this->info("There is no user in the database, cannot seed.");

            $createUsers = $this->ask('Do you want to create some users? (y/N)');

            if (!in_array(strtolower($createUsers), ['yes', 'y'])) {
                exit;
            }
            
            $userCount = $this->ask('How many users do you want to create?');

            if (!is_numeric($userCount)) {
                $userCount = 5;
            }

            factory($this->getUserModelClassName())->times($userCount)->create();
        }

        if ($this->option('verbal')) {
            $this->settings['categoryCount'] = $this->ask('How many categories should be created?');
            $this->settings['postCount'] = $this->ask('How many posts should be created?');
        }
        
        factory(Category::class)->times($this->settings['categoryCount'])->create();
        
        factory(Post::class)->times($this->settings['postCount'])->create();

        $this->info('Seeding completed.');
    }
}
