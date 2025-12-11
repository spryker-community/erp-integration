<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ErpCartValidationRequestItemTransfer;
use Generated\Shared\Transfer\ErpCheckoutCartValidationRequestCustomerTransfer;
use Generated\Shared\Transfer\ErpCheckoutCartValidationRequestTransfer;
use Generated\Shared\Transfer\ErpCheckoutCartValidationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;
use Spryker\Shared\Log\LoggerTrait;

class CheckoutCartValidator
{
    use LoggerTrait;

    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    public function validateCartBeforeCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $this->getLogger()->info('Validating order with ERP system');

        $checkoutCartValidationRequestTransfer = $this->mapQuoteToCheckoutCartValidationRequest($quoteTransfer);

        $checkoutCartValidationResponseTransfer = $this->erpIntegrationClient->validateOrder($checkoutCartValidationRequestTransfer);

        if (!$checkoutCartValidationResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->warning(
                'Order validation failed in ERP system',
                $this->provideRequestAndResponseLoggingDetails($checkoutCartValidationRequestTransfer, $checkoutCartValidationResponseTransfer),
            );

            $this->addErrorsToCheckoutResponse($checkoutCartValidationResponseTransfer, $checkoutResponseTransfer);

            return false;
        }

        return true;
    }

    protected function mapQuoteToCheckoutCartValidationRequest(QuoteTransfer $quoteTransfer): ErpCheckoutCartValidationRequestTransfer
    {
        $checkoutCartValidationRequestTransfer = new ErpCheckoutCartValidationRequestTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $checkoutCartValidationRequestTransfer->addItem(
                (new ErpCartValidationRequestItemTransfer())
                    ->setSku($itemTransfer->getSku())
                    ->setQuantity($itemTransfer->getQuantity()),
            );
        }

        if ($quoteTransfer->getShippingAddress() !== null) {
            $checkoutCartValidationRequestTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
        }
        if ($quoteTransfer->getBillingAddress() !== null) {
            $checkoutCartValidationRequestTransfer->setBillingAddress($quoteTransfer->getBillingAddress());
        }
        if ($quoteTransfer->getCustomer() !== null) {
            $checkoutCartValidationRequestTransfer->setCustomer(
                $this->mapCustomerToCheckoutCartValidationRequestCustomer($quoteTransfer->getCustomer()),
            );
        }

        return $checkoutCartValidationRequestTransfer;
    }

    protected function mapCustomerToCheckoutCartValidationRequestCustomer(CustomerTransfer $customerTransfer): ErpCheckoutCartValidationRequestCustomerTransfer
    {
        return (new ErpCheckoutCartValidationRequestCustomerTransfer())
            ->setFirstName($customerTransfer->getFirstName())
            ->setLastName($customerTransfer->getLastName())
            ->setGender($customerTransfer->getGender())
            ->setEmail($customerTransfer->getEmail())
            ->setPhone($customerTransfer->getPhone());
    }

    protected function addErrorsToCheckoutResponse(
        ErpCheckoutCartValidationResponseTransfer $checkoutCartValidationResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
    ): void {
        foreach ($checkoutCartValidationResponseTransfer->getMessages() as $message) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage($message),
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function provideRequestAndResponseLoggingDetails(
        ErpCheckoutCartValidationRequestTransfer $checkoutCartValidationRequestTransfer,
        ErpCheckoutCartValidationResponseTransfer $checkoutCartValidationResponseTransfer,
    ): array {
        return [
            'request' => $checkoutCartValidationRequestTransfer->toArray(),
            'response' => $checkoutCartValidationResponseTransfer->toArray(),
        ];
    }
}
