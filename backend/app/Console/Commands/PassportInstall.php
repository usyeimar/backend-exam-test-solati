<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

class PassportInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-passport:install
            {--personal : Create a personal access token client}
            {--password : Create a password grant client}
            {--client : Create a client credentials grant client}
            {--name= : The name of the client}
            {--provider= : The name of the user provider}
            {--redirect_uri= : The URI to redirect to after authorization }
            {--user_id= : The user ID the client should be assigned to }
            {--public : Create a public client (Auth code grant type only) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a client for issuing access tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(ClientRepository $clients)
    {
        if ($this->option('personal')) {
            $this->createPersonalClient($clients);
        } elseif ($this->option('password')) {
            $this->createPasswordClient($clients);
        } elseif ($this->option('client')) {
            $this->createClientCredentialsClient($clients);
        } else {
            $this->createAuthCodeClient($clients);
        }
    }

    /**
     * Create a new personal access client.
     *
     * @return void
     */
    protected function createPersonalClient(ClientRepository $clients)
    {
        $name = $this->option('name') ?: $this->ask(
            'What should we name the personal access client?',
            config('app.name').' Personal Access Client'
        );

        $client = $clients->createPersonalAccessClient(
            null,
            $name,
            'http://localhost'
        );

        $this->info('Personal access client created successfully.');

        $this->outputClientDetails($client, 'personal');
    }

    /**
     * Create a new password grant client.
     *
     * @return void
     */
    protected function createPasswordClient(ClientRepository $clients)
    {
        $name = $this->option('name') ?: $this->ask(
            'What should we name the password grant client?',
            config('app.name').' Password Grant Client'
        );

        $providers = array_keys(config('auth.providers'));

        $provider = $this->option('provider') ?: $this->choice(
            'Which user provider should this client use to retrieve users?',
            $providers,
            in_array('users', $providers) ? 'users' : null
        );

        $client = $clients->createPasswordGrantClient(
            null,
            $name,
            'http://localhost',
            $provider
        );

        $this->info('Password grant client created successfully.');

        $this->outputClientDetails($client, 'password');
    }

    /**
     * Create a client credentials grant client.
     *
     * @return void
     */
    protected function createClientCredentialsClient(ClientRepository $clients)
    {
        $name = $this->option('name') ?: $this->ask(
            'What should we name the client?',
            config('app.name').' ClientCredentials Grant Client'
        );

        $client = $clients->create(
            null,
            $name,
            ''
        );

        $this->info('New client created successfully.');

        $this->outputClientDetails($client);
    }

    /**
     * Create a authorization code client.
     *
     * @return void
     */
    protected function createAuthCodeClient(ClientRepository $clients)
    {
        $userId = $this->option('user_id') ?: $this->ask(
            'Which user ID should the client be assigned to? (Optional)'
        );

        $name = $this->option('name') ?: $this->ask(
            'What should we name the client?'
        );

        $redirect = $this->option('redirect_uri') ?: $this->ask(
            'Where should we redirect the request after authorization?',
            url('/auth/callback')
        );

        $client = $clients->create(
            $userId,
            $name,
            $redirect,
            null,
            false,
            false,
            ! $this->option('public')
        );

        $this->info('New client created successfully.');

        $this->outputClientDetails($client);
    }

    /**
     * Output the client's ID and secret key.
     */
    protected function outputClientDetails(Client $client, string $type = null): void
    {
        if (Passport::$hashesClientSecrets) {
            $this->line(
                '<comment>Here is your new client secret. This is the only time it will be shown so don\'t lose it!</comment>'
            );
            $this->line('');
        }

        $this->line('<comment>Client ID:</comment> '.$client->getKey());
        $this->line('<comment>Client secret:</comment> '.$client->plainSecret);

        if (file_exists($path = $this->envPath()) === false) {
            return;
        }
        $envNames = match ($type) {
            'password' => [
                'PASSPORT_PASSWORD_GRANT_CLIENT_ID=',
                'PASSPORT_PASSWORD_GRANT_CLIENT_SECRET=',
            ],
            'personal' => [
                'PASSPORT_PERSONAL_ACCESS_CLIENT_ID=',
                'PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=',
            ],
            default => throw new \Exception('Invalid type'),
        };

        if (Str::contains(file_get_contents($path), [
            $envNames[0],
            $envNames[1],
        ]) === false) {

            //Set new env values
            file_put_contents(
                $path,
                PHP_EOL.$envNames[0].$client->getKey().PHP_EOL.$envNames[1].$client->plainSecret.PHP_EOL,
                FILE_APPEND
            );

        } else {
            $config = match ($type) {
                'password' => [
                    'passport.password_grant_client.id',
                    'passport.password_grant_client.secret',
                ],
                'personal' => [
                    'passport.personal_access_client.id',
                    'passport.personal_access_client.secret',
                ],
            };

            file_put_contents(
                $path,
                str_replace(
                    $envNames[0].$this->laravel['config'][$config[0]],
                    $envNames[0].$client->getKey(),
                    file_get_contents($path)
                )
            );

            file_put_contents(
                $path,
                str_replace(
                    $envNames[1].$this->laravel['config'][$config[1]],
                    $envNames[1].$client->plainSecret,
                    file_get_contents($path)
                )
            );
        }

    }

    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env';
        }

        return $this->laravel->basePath('.env');
    }
}
