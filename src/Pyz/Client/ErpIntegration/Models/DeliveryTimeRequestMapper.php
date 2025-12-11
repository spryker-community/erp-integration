<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpDeliveryTimeRequestTransfer;
use Generated\Shared\Transfer\ErpDeliveryTimeResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class DeliveryTimeRequestMapper
{
    public function mapTransferToRequestString(ErpDeliveryTimeRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ErpDeliveryTimeResponseTransfer
    {
        $responseTransfer = (new ErpDeliveryTimeResponseTransfer());

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

        $responseTransfer->fromArray($responseData, true);

        $responseTransfer->setDeliveryTimeInSeconds($responseData['deliveryTime'] ?? 0);

        return $responseTransfer->setIsSuccessful(true);
    }
}
