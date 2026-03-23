<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ErpIntegration\Zed;

use Generated\Shared\Transfer\ErpWebhookPayloadTransfer;
use Generated\Shared\Transfer\ErpWebhookProcessResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ErpStub implements ErpStubInterface
{
    public function __construct(
        protected ZedRequestClientInterface $zedRequestClient,
    ) {
    }

    /**
     * @uses \Pyz\Zed\ErpIntegration\Business\ErpIntegrationFacadeInterface::processWebhook
     */
    public function processWebhook(
        ErpWebhookPayloadTransfer $erpWebhookPayloadTransfer,
    ): ErpWebhookProcessResponseTransfer {
        /** @var \Generated\Shared\Transfer\ErpWebhookProcessResponseTransfer $erpWebhookProcessResponseTransfer */
        $erpWebhookProcessResponseTransfer = $this->zedRequestClient->call(
            '/erp-integration/gateway/process-webhook',
            $erpWebhookPayloadTransfer,
        );

        return $erpWebhookProcessResponseTransfer;
    }
}
