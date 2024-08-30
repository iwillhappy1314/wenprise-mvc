# Wenprise MVC framework for WrodPress

Add a MVC framework to wordpress, based on [Themosis Framework](https://framework.themosis.com/) .

## Usage

### Prerequisites

1. Set permalink structure as `/%postname%/` in Permalink Settings
2. Set *Your homepage displays* as `Your latest posts` in Reading Settings

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
namespace Theme;

use Theme\Providers\RoutingService;
use Wenprise\Mvc\App;

class Init
{

    public function __construct()
    {
        $GLOBALS[ 'wenprise' ] = App::instance();

        /*
         * 获取服务容器
         */
        $container = $GLOBALS[ 'wenprise' ]->container;

        /*
         * 注册主题视图路径
         */
        $container[ 'view.finder' ]->addLocation(get_theme_file_path('templates'));


        /*
         * 加载配置文件
         */
        $container[ 'config.finder' ]->addPaths([
            get_theme_file_path('app/Config/'),
        ]);

        /**
         * 主题服务提供者
         */
        $providers = [
            RoutingService::class,
        ];

        foreach ($providers as $provider) {
            $container->register($provider);
        }
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

