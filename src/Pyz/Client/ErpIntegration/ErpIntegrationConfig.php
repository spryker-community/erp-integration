<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration;

use Pyz\Shared\ErpIntegration\ErpIntegrationConstants;
use Spryker\Client\Kernel\AbstractBundleConfig;

class ErpIntegrationConfig extends AbstractBundleConfig
{
    public function getBaseURI(): string
    {
        return $this->get(ErpIntegrationConstants::BASE_URI);
    }

    public function getLiveStockUrl(): string
    {
        return $this->get(ErpIntegrationConstants::LIVE_STOCK_URL);
    }

    public function getCartValidationUrl(): string
    {
        return $this->get(ErpIntegrationConstants::CART_VALIDATION_URL);
    }

    public function getHealthCheckUrl(): string
    {
        return $this->get(ErpIntegrationConstants::HEALTH_CHECK_URL);
    }

    public function getPricesUrl(): string
    {
        return $this->get(ErpIntegrationConstants::PRICES_URL);
    }

    public function getShippingPriceUrl(): string
    {
        return $this->get(ErpIntegrationConstants::SHIPPING_PRICE_URL);
    }

    public function getDeliveryTimeUrl(): string
    {
        return $this->get(ErpIntegrationConstants::DELIVERY_TIME_URL);
    }

    public function getOrderExportUrl(): string
    {
        return $this->get(ErpIntegrationConstants::ORDER_EXPORT_URL);
    }

    public function getCheckoutValidationUrl(): string
    {
        return $this->get(ErpIntegrationConstants::CHECKOUT_VALIDATION_URL);
    }

    public function getExampleRequestUrl(): string
    {
        return $this->get(ErpIntegrationConstants::SOME_REQUEST_URL);
    }
}
