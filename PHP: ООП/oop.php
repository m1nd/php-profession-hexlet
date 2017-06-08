<?php
 --------------

spl_autoload_register(function ($class) {
    $path = dirname(__FILE__). "/" . str_replace("\\", "/", $class) . '.php';
    require_once $path;
});
--------------
Application.php

class Application
{
    private $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function call($path)
    {
        if (isset($this->routes[$path])) {
            return [200, [], $this->routes[$path]];
        } else {
            return [404, [], "page not found"];
        }
    }
}


Middlewares/Profiler.php

class Profiler
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function call($path)
    {
        $startTime = time();
        $result = $this->app->call($path);
        $result[1]["X-Time"] = time() - $startTime;

        return $result;
    }
}
---------
tree/tags/PairedTag.php

    protected $children = [];

    public function __construct($attributes = [], $children = [])
    {
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public function toString()
    {
        $attributes = [];
        foreach ($this->getAttributes() as $key => $value) {
            $attributes[] = $key . '=' . '"' . $value . '"';
        }

        $attributeString = empty($attributes) ? '' : ' ' . implode(' ', $attributes);

        $tagData = [];
        $tagData[] = '<' . $this->getName() . $attributeString . '>';
        $tagData[] = implode('', array_map(function ($item) {
            return is_string($item) ? $item : $item->toString();
        }, $this->children));
        $tagData[] = '</' . $this->getName() . '>';

        return implode('', $tagData);
    }
 
tree/tags/SingleTag.php
    
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function toString()
    {
        $attributes = [];
        foreach ($this->getAttributes() as $key => $value) {
            $attributes[] = $key . '=' . '"' . $value . '"';
        }

        return '<' . $this->getName() . ' ' . implode(' ', $attributes) . '>';
    }
    
---------
abstract class Mailer implements MailerInterface
{
    public $vars = array();

    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function getAllVars()
    {
        return $this->vars;
    }
}

--------
Car.php
    
    public function compare(Car $car2)
    {
        $year1 = $this->getYear();
        $year2 = $car2->getYear();

        if ($year1 > $year2) {
            return -1;
        } else if ($year1 == $year2) {
            return 0;
        } else {
            return 1;
        }
    }

ComparableByAge.php

    public function isOlderThan($user)
    {
        return 1 == $this->compare($user);
    }

    public function isYoungerThan($user)
    {
        return -1 == $this->compare($user);
    }

    public function isAgeTheSame($user)
    {
        return 0 == $this->compare($user);
    }

User.php

public function compare(User $user2)
    {
        $age1 = $this->getAge();
        $age2 = $user2->getAge();

        if ($age1 > $age2) {
            return 1;
        } else if ($age1 == $age2) {
            return 0;
        } else {
            return -1;
        }
    }
--------
const MAPPING = [
        'ini' => 'App\IniConfigParser',
        'xml' => 'App\XmlConfigParser',
        'yml' => 'App\YamlConfigParser',
    ];

    public static function factory($filepath, array $options = [])
    {
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $klass = self::MAPPING[$extension];
        $data = $klass::parse($filepath, $options);
        return new self($extension, $data);
    }
--------
namespace App;

require_once 'RequiredArgsException.php';
require_once 'RequiredException.php';

class CliParser
{
    public static function parse($argsFormat, $args)
    {
            foreach ($argsFormat as $key => $value) {
            if ($value['required'] && !array_key_exists($key, $args)) {
                throw new RequiredException();
            }

            if ($value['requiredArgs']
                && (array_key_exists($key, $args) && is_null($args[$key]))) {
                throw new RequiredArgsException();
            }
        }
    }
}
-------
function read($filepath, $lambda)
{
    $handle = fopen($filepath, "r");
    try {
        $data = fread($handle, filesize($filepath));
        return $lambda($data);
    } finally {
        fclose($handle);
    }
}
------
class DynamicProps
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->data)) {
            throw new DynamicPropsUndefinedProp("{$name} is undefined");
        }

        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->data)) {
            throw new DynamicPropsUndefinedProp("{$name} is undefined");
        }

        return $this->data[$name];
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}
---------
    private $tableName;

    public function __construct($tableName)
    {
        return $this->tableName = $tableName;
    }

    public function __call($finder, $arguments)
    {
        preg_match("/findAllBy([A-Z].*)/", $finder, $outputArray);
        if (!$outputArray) {
            throw new WrongFinderNameException();
        }
        $fieldUpperName = $outputArray[1];
        preg_match_all("/([A-Z][^A-Z]+)/", $fieldUpperName, $matches);
        $fieldName = implode('_', array_map(function ($part) {
            return mb_strtolower($part);
        }, $matches[0]));

        return $this->where($fieldName, $arguments[0]);
    }
-------
   public static $tableName;

    public static function getTableName()
    {
        if (!is_null(static::$tableName)) {
           return static::$tableName;
        }

        $tableName = strtolower( substr(get_called_class(), 4) );
        return $tableName;
    }



    public static $tableName = null;

    public static function getTableName()
    {
        return static::$tableName ? static::$tableName : self::tableize(get_called_class());
    }

    public static function tableize($className)
    {
        $lastPart = end(explode('\\', $className));
        return strtolower($lastPart);
    }
-------






