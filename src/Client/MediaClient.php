<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use RuntimeException;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\GenericApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\MediaUploadApiResponse;

final class MediaClient extends BaseClient
{
    public function __construct(string $accessToken, private readonly string $phoneNumberId, HttpClient $httpClient)
    {
        parent::__construct($accessToken, $httpClient);
    }

    public function upload(string $filePath, string $mimeType): MediaUploadApiResponse
    {
        if (!is_file($filePath)) {
            throw new RuntimeException("Media file not found: {$filePath}");
        }

        $boundary = '--------------------------' . md5((string)microtime(true));
        $filename = basename($filePath);
        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new RuntimeException("Unable to read media file: {$filePath}");
        }

        $body = '';
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"messaging_product\"\r\n\r\n";
        $body .= "whatsapp\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"file\"; filename=\"{$filename}\"\r\n";
        $body .= "Content-Type: {$mimeType}\r\n\r\n";
        $body .= $content . "\r\n";
        $body .= "--{$boundary}--\r\n";

        $response = $this->sendRequest(RequestFactory::uploadMedia($this->phoneNumberId, $body, $boundary));
        return new MediaUploadApiResponse($response);
    }

    public function get(string $mediaId): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getMedia($mediaId));
        return new GenericApiResponse($response);
    }

    public function delete(string $mediaId): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::deleteMedia($mediaId));
        return new GenericApiResponse($response);
    }
}
