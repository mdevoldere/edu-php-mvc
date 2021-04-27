# MD_PHP_MVC

For educational purposes. Please do not use this on production environment.


## Usage

Create Directory for your project.

Type theses cmds inside your project directory.

> composer init
>
> composer require mdevoldere/edu-php-mvc

Create project structure like this:

- YourApp
    - Controllers
        - HomeController.php
    - Models
    - Views
        - Home
            - toto.php
        - _layout.php
- vendor
- www
    - index.php

Where `YourApp` is the directory name AND namespace of your app.

Add needed PSR4 in your composer.json

Edit files :

```php
/* www/index.php */

// Autoloader
require dirname(__DIR__).'/vendor/autoload.php';

// Set IRouter 
$router = new \Md\Http\Router('AppExample', __DIR__);

// Run App 
\Md\App::run($router);
```

```php
/* YourApp/Controllers/HomeController.php */

namespace YourApp\Controllers;

use Md\Controllers\Controller;

class HomeController extends Controller
{
    public function init()
    {
        // load your components
    }

    public function indexAction(): void
    {
        // add some data to response object
        $this->response->addData('Hello', 'World');
    }

    public function totoAction(): void
    {
        // set response as HTML response 
        // view filename is "{request->controller}/{request->action}.php"
        // ex (this action): 
        // url is "/home/toto"
        // file is "YourApp/Views/Home/toto.php"
        $this->view = true; 

        $this->response->addData('Action', 'You selected add action');
    }
}
```


```php
/* YourApp/Views/_layout.php */
<html>
<h1>Layout</h1>
<?php echo $page ?? 'Nothing to display'; ?>
</html>
```

```php
/* YourApp/Views/Home/toto.php */
/* $Action ref response->data['Action'] */
<p>Toto Page !</p>
<p><?=$Action; ?></p>
```
