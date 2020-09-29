<?php declare(strict_types=1);

namespace Boomtown;

use Github\Client;
use Github\Exception\RuntimeException;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\Response\HtmlResponse;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    public function run(): void
    {
        $html = $this->makeViewEngine()->render('index.twig', ['name' => 'Sergei']);
        $this->emitHTML($html);
    }

    private function makeViewEngine()
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