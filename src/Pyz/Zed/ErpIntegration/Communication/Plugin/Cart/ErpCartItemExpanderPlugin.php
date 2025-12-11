<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpCartItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates cart item prices from ERP system.
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getBusinessFactory()
            ->createCartItemExpander()
            ->expandCartItemsWithPrice($cartChangeTransfer);
    }
}
