<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\ErpWebhookPayloadTransfer;
use Generated\Shared\Transfer\ErpWebhookProcessResponseTransfer;

class WebHookProcessor
{
    public function processWebhook(
        ErpWebhookPayloadTransfer $webhookPayloadTransfer,
    ): ErpWebhookProcessResponseTransfer {
        $webhookProcessResponseTransfer = new ErpWebhookProcessResponseTransfer();
        $webhookProcessResponseTransfer->setIsSuccess(true);

        return $webhookProcessResponseTransfer;
    }
}
