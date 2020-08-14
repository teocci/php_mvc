## PHP-MVC

# Welcome to the PHP MVC framework

PHP-MVC is an extremely simple MVC structure for building web applications in PHP. The whole implementation is **as simple, clean and readable as possible**.
This project is **NOT a full framework**, it is a bare-bone structure, written in purely native PHP. This skeleton tries to be the opposite of big frameworks like Zend2, Symfony or Laravel.

## Why does this project exist ?

For beginners one of the main questions is **How do I build my application?**.
In PHP, particularly, it's hard to find a good and simple base structure with good information, but at the same time
there are plenty of frameworks that might be excellent, but they are really hard to use and understand 
because their complexity. This project tries to be some kind of naked skeleton for quick application building,
especially for not-advanced coders.

### Disclaimer

This repository contains a simple MVC structure for building web apps in PHP. Bug fix contributions are welcome, but issues and feature requests will not be addressed.

### Pre-requisites

- PHP
- Apache or Nginx
- Composer

## Credits

I build this project based on:

- [Write PHP like a pro: build an MVC framework from scratch][1]

## License

The code supplied here is covered under the MIT Open Source License.

---

### Goals

- Help to understand the basics of the Model-View-Controller (MVC) architecture
- Give people a clean base MVC structure to build modern PHP apps
- Encourage people to code according to PSR 1/2 coding guidelines
- Promote the usage of external libraries via Composer
- Promote the usage of PDO
- Promote development with max. error reporting
- Promote to comment code
- Promote the usage of OOP code
- Using only native PHP code, so people don't have to learn a framework

It was created for the [) course. The course explains how the framework is put together, building it step-by-step, from scratch. If you've taken the course, then you'll already know how to use it. If not, please follow the instructions below.

## Starting an application using this structure

1. First, download the framework, either directly or by cloning the repo.
1. Run **composer update** to install the project dependencies.
1. Configure your web server to have the **public** folder as the web root.
1. Open [App/Config.php][2] and enter your database configuration data.
1. Create routes, add controllers, views and models.

See below for more details.

## Configuration

The [App/Config.php][2] class stores configuration settings. Default settings include database connection data, and a setting to show or hide error detail. You can access the settings in your code like this: `Config::DB_HOST`. You can add your own configuration settings in here.

## Routing

The `add` method adds routes. You can add fixed URL routes, and specify the controller and action, like this:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts/index', ['controller' => 'Posts', 'action' => 'index']);
```

The [Router][3] translates URLs into controllers and actions. The [front controller][4] add routes. `''` routes to the `index` action in the [Home controller][5] as sample home route.

Or you can add route **variables**, like this:

```php
$router->add('{controller}/{action}');
```

In addition to the **controller** and **action**, you can specify any parameter you like within curly braces, and specify a custom regular expression for that parameter:

```php
$router->add('{controller}/{id:\d+}/{action}');
```

You can also specify a namespace for the controller:

```php
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
```

## Controllers

Controllers respond to user actions (clicking on a link, submitting a form etc.). Controllers are classes that extend the [Core\Controller][6] class.

The `App/Controllers` folder stores controllers. A sample [Home controller][5] included. Controller classes need to be in the `App/Controllers` namespace. You can add subdirectories to organise your controllers, so when adding a route for these controllers you need to specify the namespace (see the routing section above).

Controller classes contain methods that are the actions. To create an action, add the **`Action`** suffix to the method name. The sample controller in [App/Controllers/Home.php][5] has a sample `index` action.

You can access route parameters (for example the **id** parameter shown in the route examples above) in actions via the `$this->route_params` property.

### Action filters

Controllers can have **before** and **after** filter methods. These are methods that are called before and after **every** action method call in a controller. Useful for authentication for example, before letting users execute an action make sure they are logged in . Optionally add a **before-filter** to a controller like this:

```php
/**
 * Before filter. Return false to stop the action from executing.
 *
 * @return void
 */
protected function before()
{
}
```

To stop the originally called action from executing, return `false` from the before filter method. An **after-filter** is added like this:

```php
/**
 * After filter.
 *
 * @return void
 */
protected function after()
{
}
```

## Views

Display information (normally HTML) using Views. View files go in the `App/Views` folder. Views can be in one of two formats: standard PHP, but with just enough PHP to show the data. No database access or anything like that should occur in a view file. You can render a standard PHP view in a controller, optionally passing in variables, like this:

```php
View::render('Home/index.php', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```

The second format uses the [Twig][7] templating engine. Using Twig allows you to have simpler, safer templates that can take advantage of things like [template inheritance][8]. You can render a Twig template like this:

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```

A sample Twig template is included in [App/Views/Home/index.html][9] that inherits from the base template in [App/Views/base.html][10].

## Models

Models are used to get and store data in your application. They know nothing about how this data is to be presented in the views. Models extend the `Core\Model` class and use [PDO][11] to access the database. They're stored in the `App/Models` folder. A sample user model class is included in [App/Models/User.php][12]. You can get the PDO database connection instance like this:

```php
$db = static::getDB();
```

## Errors

If the `SHOW_ERRORS` configuration setting is `true`, full error detail will be shown in the browser if an error or exception occurs. If it is set to `false`, a generic message will be shown using the [App/Views/404.html][13] or [App/Views/500.html][14] views, depending on the error.

## Web server configuration

Web server rewrite rules enable pretty URLs. The `public` folder includes an [.htaccess][15] file. Equivalent nginx configuration is in the [nginx-configuration.txt][16] file.


 [1]: https://davehollingworth.net/phpmvcg
 [2]: App/Config.php
 [3]: Core/Router.php
 [4]: public/index.php
 [5]: App/Controllers/Home.php
 [6]: Core/Controller.php
 [7]: http://twig.sensiolabs.org/
 [8]: http://twig.sensiolabs.org/doc/templates.html#template-inheritance
 [9]: App/Views/Home/index.html
 [10]: App/Views/base.html
 [11]: http://php.net/manual/en/book.pdo.php
 [12]: App/Models/User.php
 [13]: App/Views/404.html
 [14]: App/Views/500.html
 [15]: public/.htaccess
 [16]: nginx-configuration.txt
 