<?php

namespace App\Collectors;

use App\Services\ChatGptService;
use Barryvdh\Debugbar\Facades\Debugbar;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class ChatGptInsightCollector extends DataCollector implements Renderable
{

    private $prompt;
    private $chatGptService;

    public function __construct()
    {
        $this->chatGptService = new ChatGptService();
        $this->prompt = "O seguinte codigo esta dando erro: {code}. Analise o erro abaixo: e me de ideias do que pode ter gerado este problema e me ajude a corrigir: {exception}";
    }

    public function collect()
    {

        $exceptionsCollector = Debugbar::getCollector('exceptions');
        $requestCollector = Debugbar::getCollector('request');

        $method = $requestCollector->collect()["data"]["controller_action"];
        $code = $this->getMethodSource($method);

        $exceptionData = $exceptionsCollector ? $exceptionsCollector->collect() : [];

        if (empty($exceptionData['exceptions'])) {
            return ['Insight' => ""];
        }

        $prompt = str_replace('{exception}', $exceptionData['exceptions'][0]['message'], $this->prompt);
        $prompt = str_replace('{code}', $code, $prompt);

        return [
                'Insight' => $this->chatGptService->talk($prompt),
        ];
    }

    public function getName()
    {
        return 'mycollector';
    }

    public function getWidgets()
    {
        return  [
            'mycollector' => [
                'widget'  => 'PhpDebugBar.Widgets.KVListWidget',
                'map'     => 'mycollector',     // nome do array retornado em collect()
                'default' => '[]',              // fallback vazio
                'title'   => 'AI Insights',     // rótulo que aparece na aba
                'icon'    => 'robot',           // ícone FontAwesome
            ],
        ];
    }

    function getMethodSource(string $classAndMethod): ?string
    {
        if (! str_contains($classAndMethod, '@')) {
            return null;
        }
        list($class, $method) = explode('@', $classAndMethod, 2);

        if (! class_exists($class) || ! method_exists($class, $method)) {
            return null;
        }

        $refMethod = new \ReflectionMethod($class, $method);
        $filename  = $refMethod->getFileName();
        $startLine = $refMethod->getStartLine();
        $endLine   = $refMethod->getEndLine();

        $lines     = file($filename, FILE_IGNORE_NEW_LINES);
        $length    = $endLine - $startLine + 1;
        $bodyLines = array_slice($lines, $startLine - 1, $length);

        return implode("\n", $bodyLines);
    }


}
