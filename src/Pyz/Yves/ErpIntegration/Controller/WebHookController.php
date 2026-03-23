<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ErpIntegration\Controller;

use Exception;
use Generated\Shared\Transfer\ErpWebhookPayloadTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Yves\ErpIntegration\ErpIntegrationFactory getFactory()
 */
class WebHookController extends AbstractController
{
    public function notificationAction(Request $request): Response
    {
        $webhookPayloadTransfer = $this->createWebhookPayload($request);

        try {
            $erpWebhookProcessResponseTransfer = $this->getFactory()
                ->getErpIntegrationClient()
                ->processWebhook($webhookPayloadTransfer);

            if (!$erpWebhookProcessResponseTransfer->getIsSuccess()) {
                return new Response(
                    'Webhook processing failed',
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }

            // TODO: Change the default response to respond with the status and content the ERP expects.
            return new Response('OK', Response::HTTP_OK);
        } catch (Exception $exception) {
            return new Response(
                'Webhook processing failed: ' . $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    protected function createWebhookPayload(Request $request): ErpWebhookPayloadTransfer
    {
        // TODO: Extract webhook data from the request and populate the transfer.
        // Parse the request body, headers, and any ERP-specific data.
        $webhookPayloadTransfer = new ErpWebhookPayloadTransfer();

        return $webhookPayloadTransfer;
    }
}
