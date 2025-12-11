<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Shared\ErpIntegration;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ErpIntegrationConstants
{
    public const string SOME_REQUEST_URL = 'ERP_INTEGRATION:SOME_REQUEST_URL';

    public const string BASE_URI = 'ERP_INTEGRATION:BASE_URI';

    public const string LIVE_STOCK_URL = 'ERP_INTEGRATION:LIVE_STOCK_URL';

    public const string CART_VALIDATION_URL = 'ERP_INTEGRATION:CART_VALIDATION_URL';

    public const string CHECKOUT_VALIDATION_URL = 'ERP_INTEGRATION:CHECKOUT_VALIDATION_URL';

    public const string HEALTH_CHECK_URL = 'ERP_INTEGRATION:HEALTH_CHECK_URL';

    public const string PRICES_URL = 'ERP_INTEGRATION:PRICES_URL';

    public const string SHIPPING_PRICE_URL = 'ERP_INTEGRATION:SHIPPING_PRICE_URL';

    public const string DELIVERY_TIME_URL = 'ERP_INTEGRATION:DELIVERY_TIME_URL';

    public const string ORDER_EXPORT_URL = 'ERP_INTEGRATION:ORDER_EXPORT_URL';
}
