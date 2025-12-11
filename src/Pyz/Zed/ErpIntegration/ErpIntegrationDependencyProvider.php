<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ErpIntegration;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ErpIntegrationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_ERP_INTEGRATION = 'CLIENT_ERP_INTEGRATION';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addErpIntegrationClient($container);

        return $container;
    }

    protected function addErpIntegrationClient(Container $container): Container
    {
        $container->set(static::CLIENT_ERP_INTEGRATION, function (Container $container) {
            return $container->getLocator()->erpIntegration()->client();
        });

        return $container;
    }
}
