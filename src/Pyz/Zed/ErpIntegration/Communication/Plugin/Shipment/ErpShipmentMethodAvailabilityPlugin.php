<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Communication\Plugin\Shipment;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpShipmentMethodAvailabilityPlugin extends AbstractPlugin implements ShipmentMethodAvailabilityPluginInterface
{
    /**
     * Specification:
     * - Filters available shipment methods based on ERP system data.
     */
    public function isAvailable(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $this->getBusinessFactory()
            ->createShipmentMethodPriceCalculator()
            ->calculateShipmentMethodPrice($shipmentGroupTransfer, $quoteTransfer) > 0;
    }
}
