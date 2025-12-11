<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CartValidationRequestItemTransfer;
use Generated\Shared\Transfer\ErpCartValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Spryker\Shared\Log\LoggerTrait;

class CartValidator
{
    use LoggerTrait;

    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    public function validateCartAfterSave(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $this->getLogger()->info('Validating cart with ERP system');

        $cartValidationRequestTransfer = $this->mapCartChangeToErpValidationRequest($cartChangeTransfer);

        $cartValidationResponseTransfer = $this->erpIntegrationClient->validateCart($cartValidationRequestTransfer);

        if (!$cartValidationResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->warning(
                'Cart validation failed in ERP system',
                $this->provideRequestAndResponseLoggingDetails($cartValidationRequestTransfer, $cartValidationResponseTransfer),
            );

            return $this->mapErpValidationResponseToCartPreCheckResponse($cartValidationResponseTransfer);
        }

        return $this->createSuccessResponse();
    }

    protected function mapCartChangeToErpValidationRequest(CartChangeTransfer $cartChangeTransfer): CartValidationRequestTransfer
    {
        $cartValidationRequestTransfer = new CartValidationRequestTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $cartValidationRequestTransfer->addItem(
                (new CartValidationRequestItemTransfer())
                    ->setSku($itemTransfer->getSku())
                    ->setQuantity($itemTransfer->getQuantity()),
            );
        }

        $quoteTransfer = $cartChangeTransfer->getQuote();
        if ($quoteTransfer !== null && $quoteTransfer->getShippingAddress() !== null) {
            $cartValidationRequestTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
        }
        if ($quoteTransfer !== null && $quoteTransfer->getBillingAddress() !== null) {
            $cartValidationRequestTransfer->setBillingAddress($quoteTransfer->getBillingAddress());
        }

        return $cartValidationRequestTransfer;
    }

    protected function mapErpValidationResponseToCartPreCheckResponse(
        ErpCartValidationResponseTransfer $cartValidationResponseTransfer,
    ): CartPreCheckResponseTransfer {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(false);

        foreach ($cartValidationResponseTransfer->getMessages() as $message) {
            $cartPreCheckResponseTransfer->addMessage(
                (new MessageTransfer())
                    ->setValue($message),
            );
        }

        return $cartPreCheckResponseTransfer;
    }

    protected function createSuccessResponse(): CartPreCheckResponseTransfer
    {
        return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @return array<string, mixed>
     */
    protected function provideRequestAndResponseLoggingDetails(
        CartValidationRequestTransfer $cartValidationRequestTransfer,
        ErpCartValidationResponseTransfer $cartValidationResponseTransfer,
    ): array {
        return [
            'request' => $cartValidationRequestTransfer->toArray(),
            'response' => $cartValidationResponseTransfer->toArray(),
        ];
    }
}
