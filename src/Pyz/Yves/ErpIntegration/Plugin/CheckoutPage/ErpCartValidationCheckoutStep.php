<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\ErpIntegration\Plugin\CheckoutPage;

use Generated\Shared\Transfer\ErpCartValidationRequestItemTransfer;
use Generated\Shared\Transfer\ErpCartValidationRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Client\ErpIntegration\ErpIntegrationClientInterface getClient()
 */
class ErpCartValidationCheckoutStep extends AbstractPlugin implements StepInterface
{
    protected const STEP_ROUTE = 'erp-cart-validation'; // TODO: adjust the step name.

    protected const ESCAPE_ROUTE = 'cart'; // TODO: adjust according to the validation location.

    public function preCondition(AbstractTransfer $dataTransfer): bool
    {
        // Check if cart validation with ERP is possible
        return true;
    }

    public function execute(Request $request, AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if (!$quoteTransfer instanceof QuoteTransfer) {
            return $quoteTransfer;
        }

        return $quoteTransfer;
    }

    protected function map(QuoteTransfer $quoteTransfer): ErpCartValidationRequestTransfer
    {
        $requestTransfer = new ErpCartValidationRequestTransfer();

        foreach ($quoteTransfer->getItems() as $item) {
            $requestTransfer->addItem(
                (new ErpCartValidationRequestItemTransfer())
                    ->setSku($item->getSku())
                    ->setQuantity($item->getQuantity()),
            );
        }

        if ($quoteTransfer->getShippingAddress()) {
            $requestTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
        }

        if ($quoteTransfer->getBillingAddress()) {
            $requestTransfer->setBillingAddress($quoteTransfer->getBillingAddress());
        }

        return $requestTransfer;
    }

    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        $requestTransfer = $this->map($quoteTransfer);

        $result = $this->getClient()->validateCart($requestTransfer);

        return $result->getIsSuccessful();
    }

    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        return false;
    }

    public function getStepRoute(): string
    {
        return static::STEP_ROUTE;
    }

    public function getEscapeRoute(): string
    {
        return static::ESCAPE_ROUTE;
    }

    public function getTemplateVariables(AbstractTransfer $dataTransfer): array
    {
        return [];
    }
}
