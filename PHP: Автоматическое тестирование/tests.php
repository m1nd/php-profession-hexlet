<?php
 --------------

TestQueryBuilder.php
        $builder = QueryBuilder::from('users');
        $expected = 'SELECT * FROM users';
        $this->assertEquals($expected, $builder->toSql());

TestQueryBuilder.php
        $builder = QueryBuilder::from('photos')->select('age', 'name');
        $expected = 'SELECT age, name FROM photos';
        $this->assertEquals($expected, $builder->toSql());

TestQueryBuilder.php
    public function testWhere()
    {
        $builder = QueryBuilder::from('users')
            ->where('age', '18')
            ->where('source', 'facebook');
        $expected = "SELECT * FROM users WHERE age = '18' AND source = 'facebook'";
        $this->assertEquals($expected, $builder->toSql());
    }

    public function testWhereWithNull()
    {
        $builder = QueryBuilder::from('users')
            ->where('email', null);
        $expected = 'SELECT * FROM users WHERE email IS NULL';
        $this->assertEquals($expected, $builder->toSql());
    }
---------
TestSolution.php

    /**
     * @dataProvider additionProvider
     */
    public function testHasEqualOnesCount($actual, $first, $second)
    {
        $this->assertEquals($actual, hasEqualOnesCount($first, $second));
    }

    public function additionProvider()
    {
        return [
            [true, 1, 1],
            [false, -1, 1],
            [false, 5, 2],
            [true, 5, 3],
        ];
    }
--------
/**
* @expectedException \InvalidArgumentException
*/
public function testException()
{
    throw new \InvalidArgumentException('mess');
}

public function test()
{
    try {
        throw new \InvalidArgumentException('mess');
        $this->fail("expected exception");
    } catch (\InvalidArgumentException $e) {
    }
}
----------
public function testAccessDenied()
{
        $acl = new Acl(static::$data);

        try {
            $acl->check('articl', 'show', 'manager');
            $this->fail("expected exception");
        } catch (Acl\ResourceUndefined $e) {
        }

        try {
            $acl->check('articles', 'create', 'manager');
            $this->fail("expected exception");
        } catch (Acl\PrivilegeUndefined $e) {
        }

        try {
            $acl->check('articles', 'edit', 'manager');
            $this->fail("expected exception");
        } catch (Acl\AccessDenied $e) {
        }

}
------------
public function setUp()
    {
        $this->data = [
            'key' => 'value',
            'deep' => [
                'key' => [],
                'deep' => 3,
                'another' => 7
            ]
        ];

        $this->config = new Config($this->data);
    }
    public function testSimpleKey()
    {
        $this->assertEquals('value' , $this->config->key);
    }

    public function testDeepKey()
    {
         $this->assertEquals(7 , $this->config->deep->another);
    }

    public function testToArray()
    {
        $this->assertEquals(['key' => [], 'deep' => 3, 'another' => 7], $this->config->deep->toArray());
    }
-----------
    private $builder;

    public function setUp()
    {
        $stub = $this->getMockBuilder('App\LoggerInterface')
            ->setMethods(['info', 'debug'])
            ->getMock();

        $this->builder = new Builder($stub);
    }

    public function testBuilderWithoutDebug()
    {
        $this->assertTrue($this->builder->build());
    }

    public function testBuilderWithDebug()
    {
        $this->assertTrue($this->builder->build(true));
    }
--------
class Sender 
{
	public $http;

	public function __construct($http)
	{
		$this->http = $http;
	}

	public function send($msg)
	{
		return $this->http->post($msg, []);
	}
}

class Http
{
	public function post($msg)
	{
		return true;
	}
}

public function testSend()
{
	$msg = 'hello, world';
	$http = $this->getMockBuilder('Http')
		->setMethods(['post'])
		->getMock();

	$http->expects($this->once())
		->mehtod('post')
		->with(
			$this->equalTo($msg),
			$this->anything()
		);

	$sender = new Sender($http);
	$sender->send($msg);
}
-------------
    private $user;
    private $connection;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder('App\DbInterface')
            ->setMethods(['query', 'transaction'])
            ->getMock();

        $this->user = new User($this->connection);
    }

    public function testSaveNew()
    {
        $this->connection->expects($this->once())
            ->method('query');

        $this->user->save();
    }

    public function testTrySaveTwice()
    {
        $this->connection->expects($this->once())
            ->method('query');

        $this->user->save();
        $this->user->save();
    }

    public function testSaveTwice()
    {
        $this->connection->expects($this->exactly(2))
            ->method('query');

        $this->user->save();

        $this->user->setFirstName('John');
        $this->user->save();
    }
----------------
function makeFolderForUser($userId, $rootDir = null)
{
	$directory = ($rootDir ? $rootDir : sys_get_temp_dir()) . DIRECTORY_SEPARTOR. $userId;

	if (!file_exists($directory)) {
		mkdir($directory, 0700, true);
	}
}


use org\bovigo\vfs\vfsStream;

class SolutionVirtualFsTest extends \PHPUnit_Framework_TestCase
{
	private $directory;
	private $userId;
	private $root;

	protected function setUp()
	{
		$this->root = vfsStream::setup('dir');

		$this->userId = 10;
		$this->directory = vfsStream::url('dir') . DIRECTORY_SEPARATOR . $this->userId;
	}

	public function testDirectoryIsCreated()
	{
		$folder = (string) $this->userId;
		$this->assertFalse($this->root->hasChild($folder));

		makeFolderForUser($this->userId, vfsStream::url('dir'));
		$this->assertTrue($this->root->hasChild($folder));
	}

}
------------
use org\bovigo\vfs\vfsStream;

    public function testMkdirs()
    {
        $root = vfsStream::setup('root');

        mkdirs(vfsStream::url('root') . '/test');
        $this->assertTrue($root->hasChild('test'));

        mkdirs(vfsStream::url('root') . '/test/inner');
        $this->assertTrue($root->hasChild('test/inner'));
    }












































