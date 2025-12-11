<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ErpIntegration\Business\ErpIntegrationBusinessFactory getBusinessFactory()
 */
class ErpHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    protected const string SERVICE_NAME = 'erp-integration';

    /**
     * Specification:
     * - Performs health check for ERP integration.
     */
    public function check(): HealthCheckServiceResponseTransfer
    {
        return $this->getBusinessFactory()
            ->createHealthChecker()
            ->executeHealthCheck();
    }

    public function getName(): string
    {
        return static::SERVICE_NAME;
    }
}
