# Wenprise MVC framework for WrodPress

Add a MVC framework to wordpress, based on [Themosis Framework](https://framework.themosis.com/) .

## Usage

### User composer to install

In your theme directory

```bash
$ composer require wenprise/mvc
```

add psr-4 autoload rule to composer.json

```json
"autoload": {
    "psr-4": {
      "Theme\\": "app/"
    }
},
```

the run the `dump-autoload` command 

```bash
$ composer dump-autoload
```

### Include the autoload and frame init fie to your theme\`s `functions.php` file.

```php
require_once( get_theme_file_path( 'vendor/autoload.php' ) );
require_once( get_theme_file_path( 'app/init.php' ) );
```

## The directory structure

The structure of theme/app directory.

```bash
├── Controllers
│   ├── AccountController.php
├── Models
│   ├── Order.php
├── Providers
│   └── RoutingService.php
├── init.php
└── routes.php
```

## Boot the framework in theme

the code in `init.php`

```php
defined( 'DS' ) ? DS : define( 'DS', DIRECTORY_SEPARATOR );


if ( function_exists( 'container' ) ) {

	/*
	 * get the container
	 */
	$theme = container();

	/*
	 * register theme view path
	 * where the blade template files placed
	 */
	$theme[ 'view.finder' ]->addLocation( get_theme_file_path( 'views' ) );

	$aliases = [];

	/*
	 * theme class alias
	 */
	if ( ! empty( $aliases ) && is_array( $aliases ) ) {
		foreach ( $aliases as $alias => $fullname ) {
			class_alias( $fullname, $alias );
		}
	}

	/**
	 * resiter theme service providers
	 */
	$providers = [
		Theme\Providers\RoutingService::class,
	];

	foreach ( $providers as $provider ) {
		$theme->register( $provider );
	}

}
```

## Routing Service 

the code in `RoutingService.php`

```php
namespace Theme\Providers;

use Wenprise\Facades\Route;
use Wenprise\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider {
	public function register() {
		Route::group( [
			'namespace' => 'Theme\Controllers',
		], function () {
			require get_theme_file_path( 'app/routes.php' );
		} );
	}
}
```

## Routers

the code in `routes.php`

```php
Route::prefix( 'account' )->group( function () {
	Route::match( [ 'get', 'post' ], 'register', 'AccountController@register' );
} );
```

## Controller

Sampel Controller

```php
namespace Theme\Controllers;

use Wenprise\Route\BaseController;

class AccountController extends BaseController {

	/**
	 * User register controller
	 * @return string
	 */
	public function register() {
	
	}
	
}
```

## Models

See: [Wenprise Eloquent](https://github.com/iwillhappy1314/wenprise-eloquent)

## Views

See https://laravel.com/docs/5.5/blade or https://twig.symfony.com/

