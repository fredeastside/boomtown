<?php declare(strict_types=1);

namespace Boomtown;

use Boomtown\Github\GithubStorage;
use Cache\Adapter\Redis\RedisCachePool;
use Github\Client;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\Response\HtmlResponse;
use Redis;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    public function run(): void
    {
        $view = [
            'isUnavailable' => false,
            'items' => [],
        ];
        try {
            $redisClient = new Redis();
            $redisClient->connect(getenv('REDIS_HOST'), (int) getenv('REDIS_PORT'));
            $pool = new RedisCachePool($redisClient);
            $client = new Client();
            $client->addCache($pool);
            $representative = RepresentativeFactory::make(new GithubStorage($client));
            $view['items'] = $representative->render();
        } catch (Throwable $e) {
            $view['isUnavailable'] = true;
        }

        $html = $this->makeViewEngine()->render('index.twig', $view);
        $this->emitHTML($html);
    }

    private function makeViewEngine(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        return new Environment($loader);
    }

    private function emitHTML(string $html): void
    {
        $response = new HtmlResponse($html);

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}