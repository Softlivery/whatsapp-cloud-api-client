<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Response;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Response\ErrorApiResponse;

class ErrorApiResponseTest extends TestCase
{
    public function testConstructorAcceptsHttpResponse()
    {
        // Arrange
        $httpResponseMock = $this->createMock(HttpResponse::class);

        // Act
        $errorApiResponse = new ErrorApiResponse($httpResponseMock);

        // Assert
        $this->assertInstanceOf(ErrorApiResponse::class, $errorApiResponse);
    }

    public function testThrowMethodReturnsApiResponseException()
    {
        // Act
        $exception = ErrorApiResponse::throw();

        // Assert
        $this->assertInstanceOf(ApiResponseException::class, $exception);
        $this->assertEquals('', $exception->getMessage());
    }
}
