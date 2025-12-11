<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;

/**
 * @template T
 */
class BaseRequest
{
    use LoggerTrait;

    protected const int DEFAULT_REQUEST_TIMEOUT_SECONDS = 5;

    protected string $modelName = '';

    public function setModelName(string $modelName): self
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function getRequestOptions(
        AbstractTransfer $requestTransfer,
        ?string $requestBody = null,
        ?int $timeout = self::DEFAULT_REQUEST_TIMEOUT_SECONDS,
    ): array {
        $options = [
            'headers' => $this->buildRequestHeaders($requestTransfer),
            'timeout' => $timeout,
        ];

        if ($requestBody) {
            $options['body'] = $requestBody;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    public function buildRequestHeaders(AbstractTransfer $requestTransfer): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @param <T> $responseTransfer
     *
     * @return <T>
     */
    public function handleFailedConnectionResponse(
        AbstractTransfer $responseTransfer,
        AbstractTransfer $requestTransfer,
        GuzzleException $exception,
        string $url,
        string $requestBody,
        array $requestOptions,
    ): AbstractTransfer {
        $this->getLogger()->error($this->modelName . ' request timed out or connection failed', [
            'exception' => $exception->getMessage(),
            'url' => $url,
            'requestOptions' => $requestOptions,
            'request' => $requestBody,
        ]);

        return $this->createFailedResponse($responseTransfer, $requestTransfer, $exception, $requestBody)
            ->addMessage($this->modelName . ' request timed out or connection failed');
    }

    /**
     * @param <T> $responseTransfer
     *
     * @return <T>
     */
    public function handleGenericRequestFailureResponse(
        AbstractTransfer $responseTransfer,
        AbstractTransfer $requestTransfer,
        GuzzleException $exception,
        string $url,
        string $requestBody,
        array $requestOptions,
    ): AbstractTransfer {
        $this->getLogger()->error($this->modelName . ' request failed', [
            'exception' => $exception->getMessage(),
            'url' => $url,
            'requestOptions' => $requestOptions,
            'request' => $requestBody,
        ]);

        return $this->createFailedResponse($responseTransfer, $requestTransfer, $exception, $requestBody);
    }

    /**
     * @param <T> $responseTransfer
     *
     * @return <T>
     */
    public function createFailedResponse(
        AbstractTransfer $responseTransfer,
        AbstractTransfer $requestTransfer,
        GuzzleException $exception,
        string $requestBody,
    ): AbstractTransfer {
        $responseTransfer->setIsSuccessful(false);
        $responseTransfer->setIsFailed(true);
        $responseTransfer->addMessage($exception->getMessage());

        return $responseTransfer;
    }

    public function logRequest(AbstractTransfer $requestTransfer, string $requestUrl, array $requestOptions): void
    {
        $this->getLogger()->info($this->modelName . ' request', [
            'request' => $requestTransfer->toArray(),
            'url' => $requestUrl,
            'requestOptions' => $requestOptions,
        ]);
    }

    public function logResponse(ResponseInterface $response, AbstractTransfer $responseTransfer): void
    {
        $this->getLogger()->info($this->modelName . ' response', [
            'response' => (string)$response->getBody(),
            'responseTransfer' => $responseTransfer->toArray(),
        ]);
    }
}
