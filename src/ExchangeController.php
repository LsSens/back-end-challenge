<?php

declare(strict_types=1);

namespace App;

/**
 * Currency exchange controller.
 */
class ExchangeController
{
    private ExchangeService $exchangeService;

    public function __construct()
    {
        $this->exchangeService = new ExchangeService();
    }

    /**
     * Process currency exchange request.
     */
    public function handleRequest(): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        
        if (!$path) {
            $this->sendErrorResponse('Invalid URL', 400);
            return;
        }

        $segments = explode('/', trim($path, '/'));
        
        if (count($segments) !== 5 || $segments[0] !== 'exchange') {
            $this->sendErrorResponse('Invalid URL format', 400);
            return;
        }

        $amount = $segments[1];
        $from = $segments[2];
        $to = $segments[3];
        $rate = $segments[4];

        try {
            $result = $this->exchangeService->convert($amount, $from, $to, $rate);
            $this->sendSuccessResponse($result);
        } catch (\InvalidArgumentException $e) {
            $this->sendErrorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            $this->sendErrorResponse('Internal server error', 500);
        }
    }

    private function sendSuccessResponse(array $data): void
    {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function sendErrorResponse(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    }
} 