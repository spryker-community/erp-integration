<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ExampleRequestTransfer;
use Generated\Shared\Transfer\ExampleResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Pyz\Client\ErpIntegration\ErpIntegrationConfig;

class ExampleRequest
{
    public function __construct(
        protected BaseRequest $baseRequest,
        protected ExampleRequestMapper $mapper,
        protected Client $guzzleClient,
        protected ErpIntegrationConfig $config,
    ) {
        $this->baseRequest->setModelName('ExampleRequest');
    }

    public function doRequest(ExampleRequestTransfer $requestTransfer): ExampleResponseTransfer
    {
        $requestUrl = $this->config->getExampleRequestUrl();
        $requestBody = $this->mapper->mapTransferToRequestString($requestTransfer);
        $requestOptions = $this->baseRequest->getRequestOptions($requestTransfer, $requestBody);

        try {
            $this->baseRequest->logRequest($requestTransfer, $requestUrl, $requestOptions);

            $response = $this->guzzleClient->post($requestUrl, $requestOptions);

            $responseTransfer = $this->mapper->mapResponseToResponseTransfer($response);

            $this->baseRequest->logResponse($response, $responseTransfer);

            return $responseTransfer;
        } catch (ConnectException $exception) {
            return $this->baseRequest->handleFailedConnectionResponse(new ExampleResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        } catch (GuzzleException $exception) {
            return $this->baseRequest->handleGenericRequestFailureResponse(new ExampleResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        }
    }
}
