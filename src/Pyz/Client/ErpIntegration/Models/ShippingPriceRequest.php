<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpShippingPriceRequestTransfer;
use Generated\Shared\Transfer\ErpShippingPriceResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Pyz\Client\ErpIntegration\ErpIntegrationConfig;

class ShippingPriceRequest
{
    public function __construct(
        protected BaseRequest $baseRequest,
        protected ShippingPriceRequestMapper $mapper,
        protected Client $guzzleClient,
        protected ErpIntegrationConfig $config,
    ) {
        $this->baseRequest->setModelName('ShippingPriceRequest');
    }

    public function doRequest(ErpShippingPriceRequestTransfer $requestTransfer): ErpShippingPriceResponseTransfer
    {
        $requestUrl = $this->config->getShippingPriceUrl();
        $requestBody = $this->mapper->mapTransferToRequestString($requestTransfer);
        $requestOptions = $this->baseRequest->getRequestOptions($requestTransfer, $requestBody);

        try {
            $this->baseRequest->logRequest($requestTransfer, $requestUrl, $requestOptions);

            $response = $this->guzzleClient->post($requestUrl, $requestOptions);

            $responseTransfer = $this->mapper->mapResponseToResponseTransfer($response);

            $this->baseRequest->logResponse($response, $responseTransfer);

            return $responseTransfer;
        } catch (ConnectException $exception) {
            return $this->baseRequest->handleFailedConnectionResponse(new ErpShippingPriceResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        } catch (GuzzleException $exception) {
            return $this->baseRequest->handleGenericRequestFailureResponse(new ErpShippingPriceResponseTransfer(), $requestTransfer, $exception, $requestUrl, $requestBody, $requestOptions);
        }
    }
}
