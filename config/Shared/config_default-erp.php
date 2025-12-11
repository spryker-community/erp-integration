<?php

use Pyz\Shared\ErpIntegration\ErpIntegrationConstants;

$config[ErpIntegrationConstants::BASE_URI] = getenv('ERP_BASE_URI');

$config[ErpIntegrationConstants::SOME_REQUEST_URL] = '/some-request/';

$config[ErpIntegrationConstants::LIVE_STOCK_URL] = '/live-stock/';
$config[ErpIntegrationConstants::CART_VALIDATION_URL] = '/cart-validation/';
$config[ErpIntegrationConstants::CHECKOUT_VALIDATION_URL] = '/checkout-validation/';
$config[ErpIntegrationConstants::PRICES_URL] = '/prices/';
$config[ErpIntegrationConstants::SHIPPING_PRICE_URL] = '/shipping-price/';
$config[ErpIntegrationConstants::DELIVERY_TIME_URL] = '/delivery-time/';
$config[ErpIntegrationConstants::ORDER_EXPORT_URL] = '/order-export/';
