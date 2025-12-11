<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Business\Model;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Pyz\Client\ErpIntegration\ErpIntegrationClientInterface;

class HealthChecker
{
    public function __construct(protected ErpIntegrationClientInterface $erpIntegrationClient)
    {
    }

    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(
                $this->erpIntegrationClient->isHealthy(),
            );
    }
}
