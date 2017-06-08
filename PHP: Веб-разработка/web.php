<?php
 --------------

function server($url)
{
    if (preg_match('/^\/about\/?$/i', $url)) {
        return "<h1>about company</h1>";
    }
}

echo server($_SERVER['REQUEST_URI']);


namespace App;

ini_set('xdebug.halt_level', E_NOTICE);
ini_set("display_errors", 1);

function server($url)
{

    $pattern = "/about/i";

	if ( preg_match($pattern, $url) == 1 ) {
		return "<h1>about company</h1>";

	}
	return "";
}

echo server($_SERVER['REQUEST_URI']);
-------------
Application.php

namespace App;

require_once 'ApplicationInterface.php';

class Application implements ApplicationInterface
{

   private $handlers = [];

    public function get($route, $handler)
    {
        $this->append('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->append('POST', $route, $handler);
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->handlers as $item) {
            list($route, $handlerMethod, $handler) = $item;
            $preparedRoute = preg_quote($route, '/');
            if ($method == $handlerMethod && preg_match("/^$preparedRoute$/i", $uri)) {
                echo $handler();
            }
        }
    }

    private function append($method, $route, $handler)
    {
        $this->handlers[] = [$route, $method, $handler];
    }

}

Application.php

<?php

namespace App;

require_once 'Application.php';

$app = new \App\Application();

$app->get('/companies', function () {
    return 'companies list';
});

$app->post('/companies', function () {
    return 'company was created';
});

$app->run();
--------------
class Application
{
	private $rotes;
	public function __construct($rotes)
	{
		$this->rotes = $rotes;
	}
	
	public function run()
	{
		//REQUEST_METHOD
		$uri = $_SERVER["REQUEST_URI"];
		foreach ($this->rotes as $item) {
			list($route, $handler) = $item;
			$preparedRoute = preg_quote($route, '/');
			if (preg_match("/^$preparedRoute$/i", $uri)) {
				echo $handler();
				return;
			}
		}	
	}
}


index.php

$rotes = [
	['/', function () {
		return '<p>main page</p>';
	}],
	['/sign_in', function () {
		return 'you signed in';
	}]
];

$app = new Application($rotes);
$app->run(); //curl localhost:3000/sign_in => 'you signed in' 
---------------
index.phtml

<h1>site <?= $site ?></h1> 								//переменная

<?php if (!empty($subprojects)) : ?> 					// условие
	<ul>
		<?php foreach ($subprojects as $project) : ?>	// цикл
			<li><?= $project ?></li>
		<?php endforeach ?>
	</ul>
<?php endif ?>



Template.php

function render($template, $variables)
{
	extract($variables);
	ob_start();
	include $template;
	return ob_get_clean();
}


index.php

$html = render('index.phtml', [
	'site' => 'hexlet.io',
	'subprojects' => ['map.hexlet.io', 'battle.hexlet.io']
]);

print_r($html);
---------------
Renderer.php
<?php
namespace App\Renderer;
require_once('Template.php');
use function App\Template\render;

function render($template, $variables = [])
{
    //Реализуйте функцию render в нейсмпейсе App\Renderer. Она принимает на вход
    //относительный путь до шаблона и параметры. Эта функция должна вычислять
    //абсолютный путь к шаблону и вызывать функцию render шаблонизатора Template

   $path = getcwd() . "/views/"  . $template . ".phtml";
   $html = render($path, $variables);
   print_r($html);
}

Template.php
<?php
namespace App\Template;

function render($template, $variables)
{
    //Реализуйте функцию render, которая принимает абсолютный путь до шаблона
    //и массив параметров, а возвращает готовый html

    extract($variables);
	ob_start();
	include $template;

	return ob_get_clean();

}
-----------------
Renderer.php

namespace App\Renderer;
require_once 'Template.php';

function render($filepath, $params = [])
{
    $templatepath = getcwd() . DIRECTORY_SEPARATOR . 'views'. DIRECTORY_SEPARATOR . $filepath . '.phtml';
    return \App\Template\Render($templatepath, $params);
}

Template.php
    
extract($variables);
ob_start();
include $template;
return ob_get_clean();
-------------
// curl -v localhost:8080?page=3&a=b
// curl -v localhost:8080/users?page=3&a=b

// index.php
<?php

namespace App;
require_once 'Application.php';

$app = new Application();

$app->get('/', function () {
	// $_REQUEST
		return json_encode($_GET);
	});

$app->post('/', function () {
	// $_REQUEST
		return json_encode($_GET);
	});

$app->run();
--------------
namespace App;
require_once 'Application.php';

$app = new Application();
$data = [
    ['id' => 4, 'age' => 15],
    ['id' => 3, 'age' => 28],
    ['id' => 8, 'age' => 3],
    ['id' => 1, 'age' => 23]
];

$app->get('/', function ($params) use ($data) {
    if (array_key_exists('sort', $params)) {
        list($key, $order) = split(' ', $params['sort']);

        usort($data, function ($prev, $next) use ($key, $order) {
            $prevValue = $prev[$key];
            $nextValue = $next[$key];

            if ($prevValue == $nextValue) {
                return 0;
            }

            if ($order == 'desc') {
                return $prevValue < $nextValue ? 1 : -1;
            } else if ($order == 'asc') {
                return $prevValue > $nextValue ? 1 : -1;
            }
        });
    }
    return json_encode($data);
});

$app->run();


namespace App;
require_once 'Application.php';

$app = new Application();
$data = [
    ['id' => 4, 'age' => 15],
    ['id' => 3, 'age' => 28],
    ['id' => 8, 'age' => 3],
    ['id' => 1, 'age' => 23]
];

$app->get('/', function () use ($data) {
    if (empty($_GET)) {
	    return json_encode($data);
    }
    $arrQuery =explode(" ", $_GET["sort"]);
    if (strcasecmp($arrQuery[1], "asc") == 0) {
        usort($data, function($a, $b) use ($arrQuery) {
                        return $a[$arrQuery[0]] - $b[$arrQuery[0]];
                        });
    } else {
    usort($data, function($a, $b) use ($arrQuery) {
                        return $b[$arrQuery[0]] - $a[$arrQuery[0]];
                        });
    }
     return json_encode($data);
    });

$app->run();
--------------
index.php

<?php
namespace App;
require_once 'Application.php';

$app = new Application();

$app->get('/users/(?P<id>\d+)', function ($params, $arguments) {
	return json_encode($arguments);
});

$app->get('/users/(?P<userId>\d+)/articles/(?P<id>[\w-]+)', function ($params, $arguments) {
	return json_encode($arguments);
});

$app->run();


Application.php

<?php
namespace App;

class Application
{
    private $handlers = [];

    public function get($route, $handler)
    {
        $this->append('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->append('POST', $route, $handler);
    }

    private function append($method, $route, $handler)
    {
        // BEGIN (write your solution here)
        ...
        // END

        $this->handlers[] = [$updatedRoute, $method, $handler];
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->handlers as $item) {
            list($route, $handlerMethod, $handler) = $item;
            $preparedRoute = str_replace('/', '\/', $route);
            $matches = [];
            if ($method == $handlerMethod && preg_match("/^$preparedRoute$/i", $uri, $matches)) {
                $arguments = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                echo $handler($_GET, $arguments);
            }
        }
    }
}
-----------------
<?php
namespace App;
class Application
{
    private $handlers = [];
    public function get($route, $handler)
    {
        $this->append('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->append('POST', $route, $handler);
    }

    private function append($method, $route, $handler)
    {
        $updatedRoute = $route;
        if (preg_match_all('/:([^\/]+)/', $route, $matches)) {
            $updatedRoute = array_reduce($matches[1], function ($acc, $value) {
                $group = "(?P<$value>[\w-]+)";
                return str_replace(":{$value}", $group, $acc);
            }, $route);
        }

        $this->handlers[] = [$updatedRoute, $method, $handler];
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->handlers as $item) {
            list($route, $handlerMethod, $handler) = $item;
            $preparedRoute = str_replace('/', '\/', $route);
            $matches = [];
            if ($method == $handlerMethod && preg_match("/^$preparedRoute$/i", $uri, $matches)) {
                $arguments = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                echo $handler($_GET, $arguments);
            }
        }
    }
}
------------------
index.php

<?php
namespace Theory;
require_once 'Application.php';

$app = new Application();

$app->get('/sign_in', function ($params, $arguments) {
	$headers = getallheaders(); //$_SERVER
	
	error_log(print_r($_SERVER, true));
	http_response_code(302);
	header("Location: http://localhost:8080");
	
	return print_r($headers, true);	
});

$app->run();

// curl -v -XPOST localhost:8080/sign_in
// curl -L -v -XPOST localhost:8080/sign_in  - переход по редиректам
-------------------
Application.php
<?php
namespace App;
class Application
{
    private $handlers = [];
    public function get($route, $handler)
    {
        $this->append('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->append('POST', $route, $handler);
    }

    private function append($method, $route, $handler)
    {
        $updatedRoute = $route;
        if (preg_match_all('/:([^\/]+)/', $route, $matches)) {
            $updatedRoute = array_reduce($matches[1], function ($acc, $value) {
                $group = "(?P<$value>[\w-]+)";
                return str_replace(":{$value}", $group, $acc);
            }, $route);
        }
        $this->handlers[] = [$updatedRoute, $method, $handler];
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->handlers as $item) {
            list($route, $handlerMethod, $handler) = $item;
            $preparedRoute = str_replace('/', '\/', $route);
            $matches = [];
            if ($method == $handlerMethod && preg_match("/^$preparedRoute$/i", $uri, $matches)) {
                $attributes = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                $meta = [
                    'method' => $method,
                    'uri' => $uri,
                    'headers' => getallheaders()
                ];

                $response = $handler($meta, array_merge($_GET, $_POST), $attributes);
                // BEGIN (write your solution here)
                http_response_code($response->getStatusCode());
                foreach ($response->getHeaderLines() as $header) {
                    header($header);
                }
                echo $response->getBody();
                // END
                return;
            }
        }
    }
}


Response.php
<?php
namespace App;
require_once 'ResponseInterface.php';

function response($body = null)
{
    return new Response($body);
}

class Response implements ResponseInterface
{
    protected $headers = [];
    protected $status = 200;
    protected $body;

    public function __construct($body)
    {
        if (is_string($body)) {
            $this->headers['Content-Length'] = mb_strlen($body);
        }
        $this->body = $body;
    }

    // BEGIN (write your solution here)
    public function redirect($url)
    {
        http_response_code(302);
	    header("Location: {$url}");
    }

    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function format($format)
    {
        switch ($format) {
            case 'json':
                $this->headers['Content-Type'] = 'application/json';
                $this->body = json_encode($this->body);
                $this->headers['Content-Length'] = mb_strlen($this->body);
        }

        return $this;
    }

    public function getStatusCode()
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaderLines()
    {
        return array_map(function ($key, $value) {
            return "$key: $value";
        }, array_keys($this->headers), $this->headers);
    }
    // END
}
-------------------
$repository = new UserRepository($pdo);
$newUser = [
	'email' => '',
	'first_name' => ''
];

$app->get('/users', function () use ($repository) {
	$users = $repository->all();
	return response(render('users/index', ['users' => $users]));
});


$app->get('/users/new', function ($meta, $params, $attributes) use ($newUser) {
	return response(render('users/new', ['errors' => [], 'user' => $newUser]));
});

$app->post('/users', function ($meta, $params, $attributes) use ($repository) {
	$user = $params['user'];
	$errors = [];

	if (!$user['email']) {
		$errors['email'] = "Email can't be blank";
	} else if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Email is not valid";
	}

	if (empty($errors)) {
		$repository->insert($user);
		return response()->redirect('users');
	} else {
		return response(render('users/new', ['user' => $user, 'errors' => $errors]))
			->withStatus(422);
	}
});

$app->run();

===
<b>Email</b>
<input type="text" name="user[email]" value="<?= $user['email'] ?>">
<?php if (isset($errors['email'])) : ?>
	<div style="color: red"><?= $errors['email'] ?></div>
<?php endif ?>

<input type="submit">
===

































































