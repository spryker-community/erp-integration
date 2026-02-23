# ERP Integration Template

Public documentation is available here: [ERP Integration Template](https://docs.spryker.com/docs/integrations/custom-building-integrations/erp-integration-template) 

## Overview

In order to connect a project to an ERP system faster, we provide a template module structure for implementing 3rd party calls.

We recommend implementing one ERP Client for each ERP system.

Please adjust the template implementation according to your project guidelines.

### Module structure overview
- config
  - Shared
    - **config_default-erp.php** - template for the configuration setup
- src
  - Pyz
    - Client
      - ErpIntegration
        - Models
          - **BaseRequest.php** - base module, providing essentials for each specific request model.
          - **[Name]Request.php** + **[Name]RequestMapper.php** - models for each type of request.
        - **ErpIntegrationClient.php** - Spryker Client entry point
    - Shared
      - ErpIntegration
        - **erp_integration.transfer.xml** - request/response transfer objects definition.
    - Yves - contains blueprint usage of the Client methods.
    - Zed - contains blueprint usage of the Client methods.

### Prepare the module structure

First, decide how to name your new module with an ERP integration. This document uses name `ErpIntegration`.

If you plan to develop a reusable standalone module, please follow [How to Create standalone modules](https://docs.spryker.com/docs/dg/dev/developing-standalone-modules/create-standalone-modules).

Next, copy the full content of Client and Shared into the module's folder, adjusting namespace if you don't use Pyz.

Then copy the provided config folder and add to the very end of **config/Shared/config_default.php** the following line:
```php
require 'config_default-erp.php';
```

### Configure ERP connection

The class `src/Pyz/Client/ErpIntegration/Models/BaseRequest` provides a request builder, logging and handling of failed responses.
You have to adjust the following places:
- `BaseRequest::DEFAULT_REQUEST_TIMEOUT_SECONDS` - with the default timeout for requests.
- `BaseRequest::getRequestOptions` - request parameters, including authentication, referrer. See [Guzzle documentation](https://docs.guzzlephp.org/en/stable/request-options.html) for a full list of options.
- `BaseRequest::buildRequestHeaders` - request headers, i.e. content type, accept, and anything 3rd party specific.

Following Spryker best practices, configuration happens in `config_default.php`, including environment-specific files.
```php
$config[ErpIntegrationConstants::BASE_URI] = getenv('ERP_BASE_URI');
$config[ErpIntegrationConstants::EXAMPLE_REQUEST_URL] = '/example-request/';
```
If your integration has fixed URLs, consider putting them directly into the ErpIntegrationConfig class.

In case of a special connection setup for [Guzzle client configuration](https://docs.guzzlephp.org/en/stable/), please apply it to `ErpIntegrationDependencyProvider::addGuzzleClient`.

### Validate logging and error handling.

Other methods follow Spryker best practices and provide detailed logs in each case:
- `BaseRequest::handleFailedConnectionResponse` - when connection failed, usually due to timeout or server error. Request is logged at error level.
- `BaseRequest::handleGenericRequestFailureResponse` - any non-connection related failure. Request is logged at error level.
- `BaseRequest::createFailedResponse` - provides a template for generating the failed response, including the message from the exception.
- `BaseRequest::logRequest` - logging request at info level.
- `BaseRequest::logResponse` - logging response at info level.

### Implement requests
First, each request requires specialized transfer objects for request and response, see `Shared/ErpIntegration/Transfer/erp_integration.transfer.xml`.

Copy and adjust transfer object definition.

```xml
    <transfer name="ExampleRequest" strict="true">
    </transfer>

    <transfer name="ExampleResponse" strict="true">
        <property name="isSuccessful" type="bool" />
        <property name="messages" type="string[]" singular="message" />
    </transfer>
```

The next step is request model preparation. Start with a copy of model `src/Pyz/Client/ErpIntegration/Models/ExampleRequest`, which doesn't require an interface since it contains a single method without a reason for extension.

Once the model is prepared, adjust the factory by creating the method `ErpIntegrationFactory::createExampleRequest`.

Adjust the name of the request method `doRequest`, if necessary. Simultaneously, create a client method to call it - `ErpIntegrationClient::doExampleRequest`.

If your system doesn't require a POST call, change the method called on guzzleClient.

To implement mapping of the request and response, create a copy of `src/Pyz/Client/ErpIntegration/Models/ExampleRequestMapper` and adjust:

- `mapTransferToRequestString` - to map request transfer object onto the 3rd party service request format.
- `mapResponseToResponseTransfer` - to map 3rd party service response format onto response transfer object.
- `isSuccessfulResponse` - to implement verification of the response's success.

When done, create a factory method and provide it into the request model.
