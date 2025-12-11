<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\ErpIntegration\Controller;

use Generated\Shared\Transfer\LiveStockRequestItemTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Client\ErpIntegration\ErpIntegrationClientInterface getClient()
 */
class StockController extends AbstractController
{
    public function indexAction(Request $request): JsonResponse
    {
        $sku = $request->query->get('sku');
        $quantity = (int)$request->query->get('quantity', 1);

        if (!$sku) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'SKU parameter is required',
            ], 400);
        }

        $requestTransfer = new LiveStockRequestTransfer();
        $requestTransfer->addItem(
            (new LiveStockRequestItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity),
        );

        $responseTransfer = $this->getClient()->getLiveStock($requestTransfer);

        if (!$responseTransfer->getIsSuccessful()) {
            return $this->jsonResponse([
                'success' => false,
                'messages' => $responseTransfer->getMessages(),
            ], 400);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $responseTransfer->getStocks()[0]->toArray(),
        ]);
    }
}
