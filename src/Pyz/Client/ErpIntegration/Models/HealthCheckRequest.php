<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpHealthCheckRequestTransfer;
use Generated\Shared\Transfer\ErpHealthCheckResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Pyz\Client\ErpIntegration\ErpIntegrationConfig;

class HealthCheckRequest
{
    public function __construct(
        protected BaseRequest $baseRequest,
        protected HealthCheckRequestMapper $mapper,
        protected Client $guzzleClient,
        protected ErpIntegrationConfig $config,
    ) {
        $this->baseRequest->setModelName('HealthCheckRequest');
    }

    public function doRequest(ErpHealthCheckRequestTransfer $requestTransfer): ErpHealthCheckResponseTransfer
    {
        $requestUrl = $this->config->getHealthCheckUrl();
        $requestBody = $this->mapper->mapTransferToRequestString($requestTransfer);
        $requestOptions = $this->baseRequest->getRequestOptions($requestTransfer, $requestBody);

        try {
            $this->baseRequest->logRequest($requestTransfer, $requestUrl, $requestOptions);

            $response = $this->guzzleClient->post($requestUrl, $requestOptions);

            $responseTransfer = $this->mapper->mapResponseToResponseTransfer($response);

            $this->baseRequest->logResponse($response, $responseTransfer);

            return $responseTransfer;
        } catch (ConnectException $exception) {
            return $this->baseRequest->handleFailedConnectionResponse(new ErpHealthCheckResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        } catch (GuzzleException $exception) {
            return $this->baseRequest->handleGenericRequestFailureResponse(new ErpHealthCheckResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        }
    }
}
