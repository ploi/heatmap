![Heatmap screenshot](/public/screenshots/screenshot.png)

# Heatmap

Welcome to Heatmap, the open-source software for your heatmapping needs 🖥

## Features

- Click tracking
- Movement tracking

## Requirements

- PHP >= 8.1
- Database (MySQL, PostgreSQL)
- Ability to configure x-frame options in your website

## Installation

First set up a database, and remember the credentials.

```
git clone https://github.com/ploi-deploy/heatmap.git
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

Now edit your `.env` file and set up the database credentials, including the app name you want.

```
php artisan heatmap:install
```

And login with the credentials you've provided, the user you've created will automatically be admin.

## Deployment

To manage your servers and sites, we recommend using [Ploi.io](https://ploi.io/?ref=roadmap-readme) to speed up things, obviously you're free to choose however you'd like to deploy this piece of software 💙

That being said, here's an deployment script example:

```sh
cd /home/ploi/example.com
git pull origin main
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
echo "" | sudo -S service php8.1-fpm reload

php artisan route:cache
php artisan view:clear
php artisan migrate --force

npm ci
npm run production

echo "🚀 Application deployed!"
```

Alternatively you can also use the upgrade command to clean up your deployment script:

```sh
cd /home/ploi/example.com
git pull origin main
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
echo "" | sudo -S service php8.1-fpm reload

php artisan heatmap:upgrade

npm ci
npm run production

echo "🚀 Application deployed!"
```

If you're using queue workers (which we recommend to do) also add `php artisan queue:restart` to your deployment script.

## Setting up your webserver to allow X-Frame-Options
Chances are, when you're setting up the heatmap software and trying to display the heatmap you'll encounter an error like:

```
Refused to display 'https://yourwebsite.com' in a frame because it set 'X-Frame-Options' to 'sameorigin'.
```

This means, it won't allow external iframes to load in your website. Luckily, this is easily solvable.

### NGINX

If you have this line in your NGINX host configuration, either remove it, or put it in comments:

```
add_header X-Frame-Options "SAMEORIGIN";

to (or remove)

#add_header X-Frame-Options "SAMEORIGIN";
```

Next add this piece of code inside the `server{}` block:

```
add_header Content-Security-Policy "frame-ancestors 'self' https://your-heatmap-address.com";
```

Obviously, replace **your-heatmap-address.com** with the actual domain where your heatmap is hosted.

### Apache

TODO

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Sponsor

We appreciate sponsors, we still maintain this repository, server, emails and domain. [You can do that here](https://github.com/sponsors/Cannonb4ll).
Each sponsor gets listed on in this readme.

## Credits

- [Cannonb4ll](https://github.com/cannonb4ll)
- [Alex](https://github.com/stayallive)
- [SebastiaanKloos](https://github.com/SebastiaanKloos)
- [Filament Admin](https://filamentadmin.com/)
- [Laravel](https://laravel.com/)
- [Ploi](https://ploi.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
