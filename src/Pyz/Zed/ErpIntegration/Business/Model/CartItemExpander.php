<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ErpPriceDataTransfer;
use Generated\Shared\Transfer\ErpPricesRequestTransfer;
use Generated\Shared\Transfer\ErpPricesResponseTransfer;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Spryker\Shared\Log\LoggerTrait;

class CartItemExpander
{
    use LoggerTrait;

    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    public function expandCartItemsWithPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $this->getLogger()->info('Expanding cart items with ERP pricing');

        $erpPricesRequestTransfer = $this->mapCartChangeToErpPricesRequest($cartChangeTransfer);

        $erpPricesResponseTransfer = $this->erpIntegrationClient->retrieveBulkPrices($erpPricesRequestTransfer);

        if (!$erpPricesResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->warning(
                'Failed to retrieve bulk prices from ERP system',
                $this->provideRequestAndResponseLoggingDetails($erpPricesRequestTransfer, $erpPricesResponseTransfer),
            );

            return $cartChangeTransfer;
        }

        return $this->mapErpPricesResponseToCartChange($erpPricesResponseTransfer, $cartChangeTransfer);
    }

    protected function mapCartChangeToErpPricesRequest(CartChangeTransfer $cartChangeTransfer): ErpPricesRequestTransfer
    {
        $erpPricesRequestTransfer = new ErpPricesRequestTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $erpPriceRequestTransfer = (new ErpPriceDataTransfer())
                ->setSku($itemTransfer->getSku())
                ->setPrice($itemTransfer->getUnitPrice() ?? 0); // TODO consider picking the right price, according to your setup, i.e. getUnitGrossPrice, getUnitNetPrice

            $erpPricesRequestTransfer->addPriceRequest($erpPriceRequestTransfer);
        }

        return $erpPricesRequestTransfer;
    }

    protected function mapErpPricesResponseToCartChange(
        ErpPricesResponseTransfer $erpPricesResponseTransfer,
        CartChangeTransfer $cartChangeTransfer,
    ): CartChangeTransfer {
        $priceMap = $this->buildPriceMapFromResponse($erpPricesResponseTransfer);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSku();
            if (!isset($priceMap[$sku])) {
                continue;
            }

            $itemTransfer->setUnitPrice($priceMap[$sku]); // TODO consider saving into the right price, according to your setup, i.e. setUnitGrossPrice, setUnitNetPrice
        }

        return $cartChangeTransfer;
    }

    /**
     * @return array<string, int>
     */
    protected function buildPriceMapFromResponse(ErpPricesResponseTransfer $erpPricesResponseTransfer): array
    {
        $priceMap = [];

        foreach ($erpPricesResponseTransfer->getPriceResponses() as $priceResponse) {
            $priceMap[$priceResponse->getSku()] = $priceResponse->getPrice();
        }

        return $priceMap;
    }

    protected function provideRequestAndResponseLoggingDetails(
        ErpPricesRequestTransfer $erpPricesRequestTransfer,
        ErpPricesResponseTransfer $erpPricesResponseTransfer,
    ): array {
        return [
            'request' => $erpPricesRequestTransfer->toArray(),
            'response' => $erpPricesResponseTransfer->toArray(),
        ];
    }
}
