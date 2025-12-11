<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpCartValidationPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * Specification:
     * - Inquiries ERP for possible issues with the cart.
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getBusinessFactory()
            ->createCartValidator()
            ->validateCartAfterSave($cartChangeTransfer);
    }
}
