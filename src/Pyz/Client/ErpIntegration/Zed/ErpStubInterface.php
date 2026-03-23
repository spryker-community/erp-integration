<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ErpIntegration\Zed;

use Generated\Shared\Transfer\ErpWebhookPayloadTransfer;
use Generated\Shared\Transfer\ErpWebhookProcessResponseTransfer;

interface ErpStubInterface
{
    public function processWebhook(
        ErpWebhookPayloadTransfer $ErpWebhookPayloadTransfer,
    ): ErpWebhookProcessResponseTransfer;
}
