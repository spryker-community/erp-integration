<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ErpDeliveryTimeRequestTransfer;
use Generated\Shared\Transfer\ErpDeliveryTimeResponseTransfer;
use Generated\Shared\Transfer\ErpItemDataTransfer;
use Generated\Shared\Transfer\ErpShippingAddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Spryker\Shared\Log\LoggerTrait;

class ShipmentMethodDeliveryTimeCalculator
{
    use LoggerTrait;

    protected const string SHIPMENT_METHOD_NAME = 'shipment_method';// TODO update the name of the method

    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    public function getShipmentMethodDeliveryTimeInSeconds(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): int
    {
        $this->getLogger()->info('Requesting delivery time from ERP system');

        $erpShippingDeliveryTimeRequestTransfer = $this->mapShipmentGroupAndQuoteToErpRequest($shipmentGroupTransfer, $quoteTransfer);

        $erpDeliveryTimeResponseTransfer = $this->erpIntegrationClient->requestDeliveryTime($erpShippingDeliveryTimeRequestTransfer);

        if (!$erpDeliveryTimeResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->warning(
                'Delivery time request to ERP system was unsuccessful',
                $this->provideRequestAndResponseLoggingDetails($erpShippingDeliveryTimeRequestTransfer, $erpDeliveryTimeResponseTransfer),
            );

            return 0;
        }

        return $erpDeliveryTimeResponseTransfer->getDeliveryTimeInSeconds() ?? 0;
    }

    protected function mapShipmentGroupAndQuoteToErpRequest(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer,
    ): ErpDeliveryTimeRequestTransfer {
        $erpShippingPriceRequestTransfer = new ErpDeliveryTimeRequestTransfer();
        $erpShippingPriceRequestTransfer->setShippingMethodName(static::SHIPMENT_METHOD_NAME);

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemDataTransfer = (new ErpItemDataTransfer())
                ->setSku($itemTransfer->getSku())
                ->setQuantity($itemTransfer->getQuantity());

            $erpShippingPriceRequestTransfer->addItem($itemDataTransfer);
        }

        $shippingAddress = $this->resolveShippingAddress($shipmentGroupTransfer, $quoteTransfer);
        if ($shippingAddress !== null) {
            $erpShippingAddressTransfer = $this->mapAddressToErpShippingAddress($shippingAddress);

            $erpShippingPriceRequestTransfer->setShippingAddress($erpShippingAddressTransfer);
        }

        return $erpShippingPriceRequestTransfer;
    }

    protected function resolveShippingAddress(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer,
    ): ?AddressTransfer {
        $shippingAddress = $shipmentGroupTransfer->getShipment()?->getShippingAddress();

        if ($shippingAddress === null) {
            $shippingAddress = $quoteTransfer->getShippingAddress();
        }

        return $shippingAddress;
    }

    protected function mapAddressToErpShippingAddress(AddressTransfer $addressTransfer): ErpShippingAddressTransfer
    {
        $erpShippingAddressTransfer = new ErpShippingAddressTransfer();

        $erpShippingAddressTransfer->setCountry($addressTransfer->getIso2Code());
        $erpShippingAddressTransfer->setCity($addressTransfer->getCity());
        $erpShippingAddressTransfer->setZipCode($addressTransfer->getZipCode());
        $erpShippingAddressTransfer->setAddress1($addressTransfer->getAddress1());
        $erpShippingAddressTransfer->setAddress2($addressTransfer->getAddress2());

        return $erpShippingAddressTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    protected function provideRequestAndResponseLoggingDetails(
        ErpDeliveryTimeRequestTransfer $erpShippingDeliveryTimeRequestTransfer,
        ErpDeliveryTimeResponseTransfer $erpDeliveryTimeResponseTransfer,
    ): array {
        return [
            'request' => $erpShippingDeliveryTimeRequestTransfer->toArray(),
            'response' => $erpDeliveryTimeResponseTransfer->toArray(),
        ];
    }
}
