<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ErpIntegration\Business;

use Generated\Shared\Transfer\ErpWebhookPayloadTransfer;
use Generated\Shared\Transfer\ErpWebhookProcessResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getFactory()
 */
class ErpIntegrationFacade extends AbstractFacade implements ErpIntegrationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function processWebhook(
        ErpWebhookPayloadTransfer $webhookPayloadTransfer
    ): ErpWebhookProcessResponseTransfer {
        return $this->getFactory()
            ->createWebHookProcessor()
            ->processWebhook($webhookPayloadTransfer);
    }
}
