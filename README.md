# LevooLabs Settings

LevooLabs Settings is a key value pair configuration manager for Laravel. The setting values are stored in the database and cached until modification.

# Installation

### Step 1: Install package

Install the package through [Composer](http://getcomposer.org/). 

Run the Composer require command from the Terminal:

    composer require levoolabs/settings
    
### Step 2: Migrations

Run migrations with artisan command:

    php artisan migrate

# Usage

You can use the `LevooLabs\Settings\Facades\Setting` facade or the helper functions to manage your configurations. (The `Setting` alias is automatically registered for the facade class.)

### Get by name

```php
$setting = Setting::get('name');
```
```php
$setting = setting('name');
```

### Set by name

```php
Setting::set('name', $value);
```

```php
setting('name', $value);
```

### Check if setting exists or not

```php
if (Setting::exists('name')) {
    //
}
```

```php
if (setting_exists('name')) {
    //
}
```

### Types

All setting values are stored as text but you can use different functions for auto typecasting. The first parameter is the name of the setting and the second is the value. With one parameter all the functions are work as a *getter* with two parameters they work as a *setter*.

```php
setting_bool('vouchers_enabled', true);
setting_int('max_voucher_per_order', 2)
setting_collection('available_countries', $country_array)
setting_json('meta_description', ['en' => '...', 'hu' => '...'])
setting_secret('my_secret', $secret)
```

```php
Setting::bool('vouchers_enabled', true);
Setting::int('max_voucher_per_order', 2)
Setting::collection('available_countries', $country_array)
Setting::json('meta_description', ['en' => '...', 'hu' => '...'])
Setting::secret('my_secret', $secret)
```

- The collection method always returns a [Collection](https://laravel.com/docs/5.6/collections). If the setting doesn't exist it returns an empty one. The `$value` for the setter can be an array or a `Collection`.
- The secret method will use Laravel's [Encrypter](https://laravel.com/docs/5.6/encryption) for encrypt and decrypt values.

### Views

Available [blade directives](https://laravel.com/docs/5.6/blade) you can use in your views:

```html
@setting('max_voucher_per_order')
```

```html
@settingexists('meta_description')
    <meta name="description" content="{{ setting_json('meta_description')->{App::getLocale()} }}">
@else
    ...
@endsettingexists
```

```html
@settingtrue('vouchers_enabled')
    <input type="text" name="voucher_code" placeholder="Coupon code" value="">
@else
    ...
@endsettingtrue
```
