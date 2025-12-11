<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business;

use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Pyz\Zed\ErpIntegration\Business\Model\CartItemExpander;
use Pyz\Zed\ErpIntegration\Business\Model\CartValidator;
use Pyz\Zed\ErpIntegration\Business\Model\CheckoutCartValidator;
use Pyz\Zed\ErpIntegration\Business\Model\HealthChecker;
use Pyz\Zed\ErpIntegration\Business\Model\OrderExporter;
use Pyz\Zed\ErpIntegration\Business\Model\ShipmentMethodDeliveryTimeCalculator;
use Pyz\Zed\ErpIntegration\Business\Model\ShipmentMethodPriceCalculator;
use Pyz\Zed\ErpIntegration\ErpIntegrationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class ErpIntegrationBusinessFactory extends AbstractBusinessFactory
{
    public function createCartValidator(): CartValidator
    {
        return new CartValidator(
            $this->getErpIntegrationClient(),
        );
    }

    public function createCheckoutCartValidator(): CheckoutCartValidator
    {
        return new CheckoutCartValidator(
            $this->getErpIntegrationClient(),
        );
    }

    public function createCartItemExpander(): CartItemExpander
    {
        return new CartItemExpander(
            $this->getErpIntegrationClient(),
        );
    }

    public function createShipmentMethodPriceCalculator(): ShipmentMethodPriceCalculator
    {
        return new ShipmentMethodPriceCalculator(
            $this->getErpIntegrationClient(),
        );
    }

    public function createShipmentMethodDeliveryTimeCalculator(): ShipmentMethodDeliveryTimeCalculator
    {
        return new ShipmentMethodDeliveryTimeCalculator(
            $this->getErpIntegrationClient(),
        );
    }

    public function createHealthChecker(): HealthChecker
    {
        return new HealthChecker(
            $this->getErpIntegrationClient(),
        );
    }

    public function createOrderExporter(): OrderExporter
    {
        return new OrderExporter(
            $this->getErpIntegrationClient(),
        );
    }

    public function getErpIntegrationClient(): ErpIntegrationClientInterface
    {
        return $this->getProvidedDependency(ErpIntegrationDependencyProvider::CLIENT_ERP_INTEGRATION);
    }
}
