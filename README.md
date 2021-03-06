# Patreon for Laravel Socialite

This package provides compatibility with version 2 of the Patreon API (the official Patreon Socialite provider is only compatible with version 1 at the time of publication).

## Installation & Basic Usage

```bash
composer require chefhasteeth/socialite-patreon
```

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/) for Socialite Providers, then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'patreon' => [    
  'client_id' => env('PATREON_CLIENT_ID'),  
  'client_secret' => env('PATREON_CLIENT_SECRET'),  
  'redirect' => env('PATREON_REDIRECT_URI') 
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \Chefhasteeth\Socialite\Patreon\PatreonExtendSocialite::class . '@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('patreon')->redirect();
```
