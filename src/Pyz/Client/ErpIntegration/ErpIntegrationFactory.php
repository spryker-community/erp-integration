<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ErpIntegration;

use GuzzleHttp\Client;
use Pyz\Client\ErpIntegration\Models\BaseRequest;
use Pyz\Client\ErpIntegration\Models\CartValidationRequest;
use Pyz\Client\ErpIntegration\Models\CartValidationRequestMapper;
use Pyz\Client\ErpIntegration\Models\CheckoutValidationRequest;
use Pyz\Client\ErpIntegration\Models\CheckoutValidationRequestMapper;
use Pyz\Client\ErpIntegration\Models\DeliveryTimeRequest;
use Pyz\Client\ErpIntegration\Models\DeliveryTimeRequestMapper;
use Pyz\Client\ErpIntegration\Models\HealthCheckRequest;
use Pyz\Client\ErpIntegration\Models\HealthCheckRequestMapper;
use Pyz\Client\ErpIntegration\Models\LiveStockRequest;
use Pyz\Client\ErpIntegration\Models\LiveStockRequestMapper;
use Pyz\Client\ErpIntegration\Models\OrderExportRequest;
use Pyz\Client\ErpIntegration\Models\OrderExportRequestMapper;
use Pyz\Client\ErpIntegration\Models\PricesRequest;
use Pyz\Client\ErpIntegration\Models\PricesRequestMapper;
use Pyz\Client\ErpIntegration\Models\ShippingPriceRequest;
use Pyz\Client\ErpIntegration\Models\ShippingPriceRequestMapper;
use Pyz\Client\ErpIntegration\Models\ExampleRequest;
use Pyz\Client\ErpIntegration\Models\ExampleRequestMapper;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Pyz\Client\ErpIntegration\ErpIntegrationConfig getConfig()
 */
class ErpIntegrationFactory extends AbstractFactory
{
    public function createLiveStockRequest(): LiveStockRequest
    {
        return new LiveStockRequest(
            $this->createBaseRequest(),
            $this->createLiveStockRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createCartValidationRequest(): CartValidationRequest
    {
        return new CartValidationRequest(
            $this->createBaseRequest(),
            $this->createCartValidationRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createHealthCheckRequest(): HealthCheckRequest
    {
        return new HealthCheckRequest(
            $this->createBaseRequest(),
            $this->createHealthCheckRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createPricesRequest(): PricesRequest
    {
        return new PricesRequest(
            $this->createBaseRequest(),
            $this->createPricesRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createShippingPriceRequest(): ShippingPriceRequest
    {
        return new ShippingPriceRequest(
            $this->createBaseRequest(),
            $this->createShippingPriceRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createDeliveryTimeRequest(): DeliveryTimeRequest
    {
        return new DeliveryTimeRequest(
            $this->createBaseRequest(),
            $this->createDeliveryTimeRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createOrderExportRequest(): OrderExportRequest
    {
        return new OrderExportRequest(
            $this->createBaseRequest(),
            $this->createOrderExportRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    public function createCheckoutCartValidationRequest(): CheckoutValidationRequest
    {
        return new CheckoutValidationRequest(
            $this->createBaseRequest(),
            $this->createCheckoutValidationRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    protected function createBaseRequest(): BaseRequest
    {
        return new BaseRequest();
    }

    protected function createLiveStockRequestMapper(): LiveStockRequestMapper
    {
        return new LiveStockRequestMapper();
    }

    protected function createCartValidationRequestMapper(): CartValidationRequestMapper
    {
        return new CartValidationRequestMapper();
    }

    protected function createHealthCheckRequestMapper(): HealthCheckRequestMapper
    {
        return new HealthCheckRequestMapper();
    }

    protected function createPricesRequestMapper(): PricesRequestMapper
    {
        return new PricesRequestMapper();
    }

    protected function createShippingPriceRequestMapper(): ShippingPriceRequestMapper
    {
        return new ShippingPriceRequestMapper();
    }

    protected function createDeliveryTimeRequestMapper(): DeliveryTimeRequestMapper
    {
        return new DeliveryTimeRequestMapper();
    }

    protected function createOrderExportRequestMapper(): OrderExportRequestMapper
    {
        return new OrderExportRequestMapper();
    }

    protected function createCheckoutValidationRequestMapper(): CheckoutValidationRequestMapper
    {
        return new CheckoutValidationRequestMapper();
    }

    public function createExampleRequest(): ExampleRequest
    {
        return new ExampleRequest(
            $this->createBaseRequest(),
            $this->createExampleRequestMapper(),
            $this->getGuzzleClient(),
            $this->getConfig(),
        );
    }

    protected function createExampleRequestMapper(): ExampleRequestMapper
    {
        return new ExampleRequestMapper();
    }

    public function getGuzzleClient(): Client
    {
        return $this->getProvidedDependency(ErpIntegrationDependencyProvider::CLIENT_GUZZLE);
    }
}
