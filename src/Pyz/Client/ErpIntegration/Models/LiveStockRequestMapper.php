<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpLiveStockRequestTransfer;
use Generated\Shared\Transfer\ErpLiveStockResponseItemTransfer;
use Generated\Shared\Transfer\ErpLiveStockResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class LiveStockRequestMapper
{
    public function mapTransferToRequestString(ErpLiveStockRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ErpLiveStockResponseTransfer
    {
        $responseTransfer = (new ErpLiveStockResponseTransfer());

        $responseData = json_decode((string)$response->getBody(), true);

        if (!$responseData) {
            return $responseTransfer->setIsSuccessful(false);
        }

        $responseTransfer->setIsSuccessful($responseData['isSuccessful'] ?? false);

        if (isset($responseData['messages']) && is_array($responseData['messages'])) {
            foreach ($responseData['messages'] as $message) {
                $responseTransfer->addMessage($message);
            }
        }

        if (empty($responseData['stocks']) || !is_array($responseData['stocks']) || !$responseTransfer->getIsSuccessful()) {
            return $responseTransfer
                ->addMessage('No stock entries were found.')
                ->setIsSuccessful(false);
        }

        foreach ($responseData['stocks'] as $stockData) {
            if (empty($stockData['sku'])) {
                continue;
            }

            $responseTransfer->addStock(
                (new ErpLiveStockResponseItemTransfer())
                    ->setSku($stockData['sku'])
                    ->setAvailability($stockData['availability'])
                    ->setStock($stockData['stock'] ?? 0),
            );
        }

        return $responseTransfer->setIsSuccessful(true);
    }
}
