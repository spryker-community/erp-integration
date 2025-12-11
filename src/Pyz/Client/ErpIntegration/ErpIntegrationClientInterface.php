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
use Generated\Shared\Transfer\ErpLiveStockRequestTransfer;
use Generated\Shared\Transfer\ErpLiveStockResponseTransfer;
use Generated\Shared\Transfer\ErpOrderExportRequestTransfer;
use Generated\Shared\Transfer\ErpOrderExportResponseTransfer;
use Generated\Shared\Transfer\ErpPricesRequestTransfer;
use Generated\Shared\Transfer\ErpPricesResponseTransfer;
use Generated\Shared\Transfer\ErpShippingPriceRequestTransfer;
use Generated\Shared\Transfer\ErpShippingPriceResponseTransfer;

interface ErpIntegrationClientInterface
{
    public function getLiveStock(
        ErpLiveStockRequestTransfer $requestTransfer,
    ): ErpLiveStockResponseTransfer;

    public function validateCart(
        ErpCartValidationRequestTransfer $requestTransfer,
    ): ErpCartValidationResponseTransfer;

    public function isHealthy(): bool;

    public function retrieveBulkPrices(
        ErpPricesRequestTransfer $requestTransfer,
    ): ErpPricesResponseTransfer;

    public function requestShippingPrice(
        ErpShippingPriceRequestTransfer $requestTransfer,
    ): ErpShippingPriceResponseTransfer;

    public function requestDeliveryTime(ErpDeliveryTimeRequestTransfer $requestTransfer): ErpDeliveryTimeResponseTransfer;

    public function exportOrder(ErpOrderExportRequestTransfer $requestTransfer): ErpOrderExportResponseTransfer;

    public function validateOrder(
        ErpCheckoutCartValidationRequestTransfer $requestTransfer,
    ): ErpCheckoutCartValidationResponseTransfer;
}
