<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpShippingPriceRequestTransfer;
use Generated\Shared\Transfer\ErpShippingPriceResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class ShippingPriceRequestMapper
{
    public function mapTransferToRequestString(ErpShippingPriceRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ErpShippingPriceResponseTransfer
    {
        $responseTransfer = (new ErpShippingPriceResponseTransfer());

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

        $responseTransfer->setShippingPrice($responseData['shippingPrice'] ?? 0);

        return $responseTransfer->setIsSuccessful(true);
    }
}
