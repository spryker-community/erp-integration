<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Pyz\Client\ErpIntegration\ErpIntegrationConfig getConfig()
 */
class ErpIntegrationDependencyProvider extends AbstractDependencyProvider
{
    public const string CLIENT_GUZZLE = 'CLIENT_GUZZLE';

    public const string CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addGuzzleClient($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    protected function addGuzzleClient(Container $container): Container
    {
        $container->set(static::CLIENT_GUZZLE, function () {
            return new Client([
                'base_uri' => $this->getConfig()->getBaseURI(),
            ]);
        });

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }
}
