<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\ErpItemDataTransfer;
use Generated\Shared\Transfer\ErpOrderExportRequestTransfer;
use Generated\Shared\Transfer\ErpOrderExportResponseTransfer;
use Generated\Shared\Transfer\ErpTotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Spryker\Shared\Log\LoggerTrait;

class OrderExporter
{
    use LoggerTrait;

    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function exportOrder(SpySalesOrder $orderEntity): array
    {
        $this->getLogger()->info('Exporting order to ERP system', ['orderReference' => $orderEntity->getOrderReference()]);

        $erpOrderExportRequestTransfer = $this->mapOrderEntityToErpRequest($orderEntity);

        $erpOrderExportResponseTransfer = $this->erpIntegrationClient->exportOrder($erpOrderExportRequestTransfer);

        if (!$erpOrderExportResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->warning(
                'Order export to ERP system was unsuccessful',
                $this->provideRequestAndResponseLoggingDetails($erpOrderExportRequestTransfer, $erpOrderExportResponseTransfer),
            );

            return [];
        }

        return $erpOrderExportResponseTransfer->toArray();
    }

    protected function mapOrderEntityToErpRequest(SpySalesOrder $orderEntity): ErpOrderExportRequestTransfer
    {
        $erpOrderExportRequestTransfer = new ErpOrderExportRequestTransfer();

        $erpOrderExportRequestTransfer->setOrderReference($orderEntity->getOrderReference());
        $erpOrderExportRequestTransfer->setCustomerReference($orderEntity->getCustomerReference());

        foreach ($orderEntity->getItems() as $itemEntity) {
            $erpOrderExportRequestTransfer->addItem(
                (new ErpItemDataTransfer())
                    ->setSku($itemEntity->getSku())
                    ->setQuantity($itemEntity->getQuantity())
            );
        }

        $erpOrderExportRequestTransfer->setTotals(
            (new ErpTotalsTransfer())
                ->fromArray($orderEntity->getOrderTotals()->toArray(), true)
        );

        return $erpOrderExportRequestTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    protected function provideRequestAndResponseLoggingDetails(
        ErpOrderExportRequestTransfer $erpOrderExportRequestTransfer,
        ErpOrderExportResponseTransfer $erpOrderExportResponseTransfer,
    ): array {
        return [
            'request' => $erpOrderExportRequestTransfer->toArray(),
            'response' => $erpOrderExportResponseTransfer->toArray(),
        ];
    }
}
