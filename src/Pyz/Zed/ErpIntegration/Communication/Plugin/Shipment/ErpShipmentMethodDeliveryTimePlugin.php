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
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpShipmentMethodDeliveryTimePlugin extends AbstractPlugin implements ShipmentMethodDeliveryTimePluginInterface
{
    /**
     * Specification:
     * - Request shipment delivery time from ERP.
     */
    public function getTime(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): int
    {
        return $this->getBusinessFactory()
            ->createShipmentMethodDeliveryTimeCalculator()
            ->getShipmentMethodDeliveryTimeInSeconds($shipmentGroupTransfer, $quoteTransfer);
    }
}
