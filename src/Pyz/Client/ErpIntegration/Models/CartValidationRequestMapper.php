<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ErpCartValidationRequestTransfer;
use Generated\Shared\Transfer\ErpCartValidationResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class CartValidationRequestMapper
{
    public function mapTransferToRequestString(ErpCartValidationRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ErpCartValidationResponseTransfer
    {
        $responseTransfer = (new ErpCartValidationResponseTransfer());

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

        return $responseTransfer->setIsSuccessful(true);
    }
}
