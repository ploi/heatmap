<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\CanShowAnIntro;
use App\Models\User;
use Filament\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class Install extends Command
{
    use CanValidateInput, CanShowAnIntro;

    protected $signature = 'heatmap:install';

    protected $description = 'Install Heatmap software.';

    public function handle()
    {
        $this->intro();
        $this->refreshDatabase();
        $this->createUser();
        $this->linkStorage();
        $this->runNpm();

        $this->askForStar();

        $this->writeSeparationLine();
        $this->line(' ');

        $this->line('Be sure to also configure your website\'s webserver if you get an X-Frame-Options error.');
        $this->line('More information: https://github.com/ploi-deploy/heatmap#setting-up-your-webserver-to-allow-x-frame-options');

        $this->line(' ');
        $this->writeSeparationLine();
        $this->line(' ');

        $this->info('All done! You can now login at '.route('filament.auth.login'));
    }

    protected function refreshDatabase()
    {
        if ($this->confirm('Do you want to run the migrations to set up everything fresh? (php artisan migrate:fresh)')) {
            $this->call('migrate:fresh');
        }
    }

    protected function createUser()
    {
        $this->info('Let\'s create a user.');

        $user = User::create($this->getUserData());
        $user->email_verified_at = now();
        $user->save();

        $this->info('User created!');

        return $user;
    }

    protected function linkStorage()
    {
        if (! file_exists(public_path('storage')) && $this->confirm('Your storage does not seem to be linked, do you want me to do this?')) {
            $this->call('storage:link');
        }
    }

    protected function runNpm()
    {
        if ($this->confirm('Do you want to run npm ci & npm run production to get the assets ready?')) {
            $this->info('Running NPM..');

            shell_exec('npm ci');
            shell_exec('npm run production');

            $this->info('NPM installation & mixing production done!');
        }
    }

    protected function askForStar()
    {
        if (User::count() === 1 && $this->confirm('Would you like to show some love by starring the repo?', true)) {
            if (PHP_OS_FAMILY === 'Darwin') {
                exec('open https://github.com/ploi-deploy/heatmap');
            }
            if (PHP_OS_FAMILY === 'Linux') {
                exec('xdg-open https://github.com/ploi-deploy/heatmap');
            }
            if (PHP_OS_FAMILY === 'Windows') {
                exec('start https://github.com/ploi-deploy/heatmap');
            }
        }
    }

    protected function getUserData(): array
    {
        return [
            'name' => $this->validateInput(fn () => $this->ask('Name'), 'name', ['required']),
            'email' => $this->validateInput(fn () => $this->ask('Email address'), 'email', ['required', 'email', 'unique:'.User::class]),
            'password' => Hash::make($this->validateInput(fn () => $this->secret('Password'), 'password', ['required', 'min:8'])),
        ];
    }
}
