<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration\Models;

use Generated\Shared\Transfer\ExampleRequestTransfer;
use Generated\Shared\Transfer\ExampleResponseTransfer;
use Psr\Http\Message\ResponseInterface;

class ExampleRequestMapper
{
    public function mapTransferToRequestString(ExampleRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponseToResponseTransfer(ResponseInterface $response): ExampleResponseTransfer
    {
        $responseTransfer = new ExampleResponseTransfer();

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
