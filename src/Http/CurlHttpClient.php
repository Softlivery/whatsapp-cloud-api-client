<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http;

use RuntimeException;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

final class CurlHttpClient implements HttpClient
{
    /** @var array<string,string> */
    private array $defaultHeaders;

    /**
     * @param array<string,string> $defaultHeaders
     */
    public function __construct(
        private readonly string $baseUrl = 'https://graph.facebook.com',
        private readonly ?string $graphVersion = 'v25.0',
        array $defaultHeaders = []
    ) {
        $this->defaultHeaders = $defaultHeaders;
    }

    public function send(ApiRequest $request): HttpResponse
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('cURL extension is required for CurlHttpClient');
        }

        $responseHeaders = [];
        $ch = curl_init($this->buildUrl($request->getPath()));

        if ($ch === false) {
            throw new RuntimeException('Failed to initialize cURL');
        }

        $headers = $this->formatHeaders(array_merge($this->defaultHeaders, $request->getHeaders()));

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $request->getTimeout(),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_ENCODING => '',
            CURLOPT_HEADERFUNCTION => static function ($curl, string $line) use (&$responseHeaders): int {
                $trimmed = trim($line);
                if ($trimmed === '' || !str_contains($trimmed, ':')) {
                    return strlen($line);
                }

                [$name, $value] = explode(':', $trimmed, 2);
                $name = trim($name);
                $value = trim($value);

                if (!isset($responseHeaders[$name])) {
                    $responseHeaders[$name] = [];
                }
                $responseHeaders[$name][] = $value;

                return strlen($line);
            },
        ];

        $body = $request->getBody();
        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($ch, $options);

        $responseBody = curl_exec($ch);
        if ($responseBody === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('cURL request failed: ' . $error);
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return new HttpResponse(
            $responseBody,
            is_int($statusCode) && $statusCode > 0 ? $statusCode : null,
            $responseHeaders
        );
    }

    protected function buildUrl(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if ($this->graphVersion !== null && $this->graphVersion !== '') {
            $normalizedVersion = trim($this->graphVersion, '/');
            if (!preg_match('#^v\d+(\.\d+)?/#i', $normalizedPath)) {
                $normalizedPath = $normalizedVersion . '/' . $normalizedPath;
            }
        }

        return rtrim($this->baseUrl, '/') . '/' . $normalizedPath;
    }

    /**
     * @param array<string,string|array<int,string>> $headers
     * @return array<int,string>
     */
    private function formatHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $result[] = $name . ': ' . $item;
                }
                continue;
            }

            $result[] = $name . ': ' . $value;
        }

        return $result;
    }
}
