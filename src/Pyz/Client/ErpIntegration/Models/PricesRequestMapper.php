<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpPriceResponseTransfer;
use Generated\Shared\Transfer\ErpPricesRequestTransfer;
use Generated\Shared\Transfer\ErpPricesResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class PricesRequestMapper
{
    public function mapTransferToRequestString(ErpPricesRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ErpPricesResponseTransfer
    {
        $responseTransfer = (new ErpPricesResponseTransfer());

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

        if (!$responseTransfer->getIsSuccessful()) {
            return $responseTransfer;
        }

        if (empty($responseData['priceResponses']) || !is_array($responseData['priceResponses'])) {
            return $responseTransfer
                ->addMessage('No price entries were found.')
                ->setIsSuccessful(false);
        }

        foreach ($responseData['priceResponses'] as $priceData) {
            if (empty($priceData['sku'])) {
                continue;
            }

            $responseTransfer->addPriceResponse((new ErpPriceResponseTransfer())
                ->setSku($priceData['sku'])
                ->setPrice($priceData['price'] ?? 0));
        }

        return $responseTransfer->setIsSuccessful(true);
    }
}
