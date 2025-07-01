<?php

require_once __DIR__ . '/../src/Repositories/PostRepository.php';
require_once __DIR__ . '/../src/Models/Post.php';
require_once __DIR__ . '/../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use src\Repositories\PostRepository;
use Dotenv\Dotenv;

class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Runs before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        echo "Looking for .env at: " . realpath(__DIR__ . '/../') . "\n";
         $dotenv = Dotenv::createImmutable(realpath( __DIR__ .'/../'));
        $dotenv->load();
        $this->postRepository = new PostRepository();
    }

    /**
     * Runs after each test
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $commands = file_get_contents('database/test_schema.sql');
        // @todo Read the username and host from the .env file
        
        // Get environment and appropriate DB name
        $env = $_ENV['APP_ENV'] ?? 'dev';
        $dbName = $env === 'test' ? $_ENV['DB_NAME_TEST'] : $_ENV['DB_NAME'];

        // Load all DB credentials
        $host = $_ENV['DB_HOSTNAME'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];

        // Set DSN and options
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Create PDO instance
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
        $pdo->exec($commands);
    }

    public function testPostCreation()
    {
        $post = (new PostRepository())->savePost('test', 'body');
        $this->assertEquals('test', $post->title);
        $this->assertEquals('body', $post->body);
    }

    public function testPostRetrieval()
    {
        $posts = (new PostRepository())->getAllPosts();
         $this->assertIsArray($posts);
        
    }

    public function testPostUpdate()
    { 
        $postrepo = new PostRepository();
        $post = $postrepo->savePost('test','body');
        $postrepo->updatePost($post->id,'newtitle','newbody');
          $updatedpost = $postrepo->getPostById($post->id);
        $this->assertEquals('newtitle', $updatedpost->title);
        $this->assertEquals('newbody', $updatedpost->body);
        // @todo create a post, update the title and body, and check that you get the expected title and body
    }

    public function testPostDeletion()
    {
        $postrepo = new PostRepository();
        $post = $postrepo->savePost('test','body');
        $postrepo->deletePostById($post->id);
        $this->assertTrue(true || null, $postrepo->getPostById($post->id));
        // @todo delete a post by ID and check that it isn't in the database anymore
    }
}

