<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration;

use Generated\Shared\Transfer\ErpCartValidationRequestTransfer;
use Generated\Shared\Transfer\ErpCartValidationResponseTransfer;
use Generated\Shared\Transfer\ErpCheckoutCartValidationRequestTransfer;
use Generated\Shared\Transfer\ErpCheckoutCartValidationResponseTransfer;
use Generated\Shared\Transfer\ErpDeliveryTimeRequestTransfer;
use Generated\Shared\Transfer\ErpDeliveryTimeResponseTransfer;
use Generated\Shared\Transfer\ErpHealthCheckRequestTransfer;
use Generated\Shared\Transfer\ErpLiveStockRequestTransfer;
use Generated\Shared\Transfer\ErpLiveStockResponseTransfer;
use Generated\Shared\Transfer\ErpOrderExportRequestTransfer;
use Generated\Shared\Transfer\ErpOrderExportResponseTransfer;
use Generated\Shared\Transfer\ErpPricesRequestTransfer;
use Generated\Shared\Transfer\ErpPricesResponseTransfer;
use Generated\Shared\Transfer\ErpShippingPriceRequestTransfer;
use Generated\Shared\Transfer\ErpShippingPriceResponseTransfer;
use Generated\Shared\Transfer\ExampleRequestTransfer;
use Generated\Shared\Transfer\ExampleResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ErpIntegration\ErpIntegrationFactory getFactory()
 */
class ErpIntegrationClient extends AbstractClient implements ErpIntegrationClientInterface
{
    public function getLiveStock(ErpLiveStockRequestTransfer $requestTransfer): ErpLiveStockResponseTransfer
    {
        return $this->getFactory()
            ->createLiveStockRequest()
            ->doRequest($requestTransfer);
    }

    public function validateCart(ErpCartValidationRequestTransfer $requestTransfer): ErpCartValidationResponseTransfer
    {
        return $this->getFactory()
            ->createCartValidationRequest()
            ->doRequest($requestTransfer);
    }

    public function isHealthy(): bool
    {
        $responseTransfer = $this->getFactory()
            ->createHealthCheckRequest()
            ->doRequest(new ErpHealthCheckRequestTransfer());

        return $responseTransfer->getIsSuccessful();
    }

    public function retrieveBulkPrices(ErpPricesRequestTransfer $requestTransfer): ErpPricesResponseTransfer
    {
        return $this->getFactory()
            ->createPricesRequest()
            ->doRequest($requestTransfer);
    }

    public function requestShippingPrice(ErpShippingPriceRequestTransfer $requestTransfer): ErpShippingPriceResponseTransfer
    {
        return $this->getFactory()
            ->createShippingPriceRequest()
            ->doRequest($requestTransfer);
    }

    public function requestDeliveryTime(ErpDeliveryTimeRequestTransfer $requestTransfer): ErpDeliveryTimeResponseTransfer
    {
        return $this->getFactory()
            ->createDeliveryTimeRequest()
            ->doRequest($requestTransfer);
    }

    public function exportOrder(ErpOrderExportRequestTransfer $requestTransfer): ErpOrderExportResponseTransfer
    {
        return $this->getFactory()
            ->createOrderExportRequest()
            ->doRequest($requestTransfer);
    }

    public function validateOrder(ErpCheckoutCartValidationRequestTransfer $requestTransfer): ErpCheckoutCartValidationResponseTransfer
    {
        return $this->getFactory()
            ->createCheckoutCartValidationRequest()
            ->doRequest($requestTransfer);
    }

    public function doExampleRequest(ExampleRequestTransfer $requestTransfer): ExampleResponseTransfer
    {
        return $this->getFactory()
            ->createExampleRequest()
            ->doRequest($requestTransfer);
    }
}
