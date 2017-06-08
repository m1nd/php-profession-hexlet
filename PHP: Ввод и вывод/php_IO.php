<?php
 --------------

function cd($current, $move)
{ 
  	if (0 === strpos('/', $move)) {
        return $move;
    }

    $currentParts = explode(DIRECTORY_SEPARATOR, $current);
    $parts = explode(DIRECTORY_SEPARATOR, $move);
    $updatedParts = array_reduce($parts, function ($acc, $item) {
        switch ($item) {
            case '.':
                return $acc;
            case '..':
                return array_slice($acc, 0, -1);
            case '':
                return $acc;
            default:
                $acc[] = $item;
                return $acc;
        }
    }, $currentParts);

    return implode(DIRECTORY_SEPARATOR, $updatedParts);
}
--------
function rrmdir($dir)
{
  if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (is_dir($dir."/".$object))
           rrmdir($dir."/".$object);
         else
           unlink($dir."/".$object);
       }
     }
     rmdir($dir);
   }
}


function rrmdir($dir) 
{
	    $dirIterator = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
    $iterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $filename => $fileInfo) {
        if ($fileInfo->isDir()) {
            rmdir($filename);
        } else {
            unlink($filename);
        }
    }
    rmdir($dir);
}
--------
function grep($string, $path)
{
    $iterGlobal = new \GlobIterator($path);
    $res = [];

    foreach ($iterGlobal as $item) {
        $file = new \SplFileObject( $item->getFilename() );
        foreach ($file as $lineNumber => $content) {
	       // printf("Line %d: %s", $lineNumber, $content);
	       if (strpos($content, $string) !== false) {
	           $res[] = $content;
	       }
        }
    }
    return $res;
 }
---------
function load($file)
{
    $data = file_get_contents($file);
    return unserialize($data);
}

function dump($file, $data)
{
    $string = serialize($data);
    file_put_contents($file, $string);
}


function dump($file, $structure)
{
    print_r($structure);
    $fileW = new \SplFileObject($file, 'ab');
    $fileW->fwrite(serialize($structure));

}

function load($file)
{
    $res = [];
    $fileR = new \SplFileObject( $file );
    foreach ($fileR as $lineNumber => $content) {
        $res = unserialize($content);
    }
    return $res;
}
--------
//Db.php

class Db
{
    const KEY_LENGTH = 8;
    const VALUE_LENGTH = 100;
  
    const ZERO = 0x00;

    private $db;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            touch($file);
        }
        $this->db = new \SplFileObject($file, 'r+');
    }

    public function get($key)
    {
        $this->db->rewind();
        while (!$this->db->eof()) {
            $possibleKey = trim($this->db->fread(self::KEY_LENGTH), self::ZERO);
            if ($key === $possibleKey) {
                return trim($this->db->fread(self::VALUE_LENGTH), self::ZERO);
            }
        }

        throw new Db\NotFoundException("'$key' is not exists");
    }

    public function set($key, $value)
    {
        $this->db->rewind();
        while (!$this->db->eof()) {
            $possibleKey = trim($this->db->fread(self::KEY_LENGTH), self::ZERO);
            if ($key === $possibleKey) {
                $this->write($value, self::VALUE_LENGTH);
                return;
            }
        }

        $this->write($key, self::KEY_LENGTH);
        $this->write($value, self::VALUE_LENGTH);
    }

    private function write($data, $length)
    {
        $zeroLength = $length - strlen($data);
        $this->db->fwrite($data);
        $this->db->fwrite(str_repeat(self::ZERO, $zeroLength));
    }
 
}
----------
function tmpdir($func)
{
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();
    mkdir($dir);
    try {
        return $func($dir);
    } finally {
        rrmdir($dir);
    }
}
--------
























