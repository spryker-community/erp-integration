<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * Specification:
     * - Inquiries ERP for possible issues with the soon-to-be order.
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getBusinessFactory()
            ->createCheckoutCartValidator()
            ->validateCartBeforeCheckout($quoteTransfer, $checkoutResponseTransfer);
    }
}
