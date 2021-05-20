<?php

namespace Mollie\Api;

use _PhpScopereac699eb1a3f\Composer\CaBundle\CaBundle;
use _PhpScopereac699eb1a3f\GuzzleHttp\Client;
use _PhpScopereac699eb1a3f\GuzzleHttp\ClientInterface;
use _PhpScopereac699eb1a3f\GuzzleHttp\Exception\GuzzleException;
use _PhpScopereac699eb1a3f\GuzzleHttp\HandlerStack;
use _PhpScopereac699eb1a3f\GuzzleHttp\Psr7\Request;
use _PhpScopereac699eb1a3f\GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Mollie\Api\Endpoints\ChargebackEndpoint;
use Mollie\Api\Endpoints\CustomerEndpoint;
use Mollie\Api\Endpoints\CustomerPaymentsEndpoint;
use Mollie\Api\Endpoints\InvoiceEndpoint;
use Mollie\Api\Endpoints\MandateEndpoint;
use Mollie\Api\Endpoints\MethodEndpoint;
use Mollie\Api\Endpoints\OnboardingEndpoint;
use Mollie\Api\Endpoints\OrderEndpoint;
use Mollie\Api\Endpoints\OrderLineEndpoint;
use Mollie\Api\Endpoints\OrderPaymentEndpoint;
use Mollie\Api\Endpoints\OrderRefundEndpoint;
use Mollie\Api\Endpoints\OrganizationEndpoint;
use Mollie\Api\Endpoints\PaymentCaptureEndpoint;
use Mollie\Api\Endpoints\PaymentChargebackEndpoint;
use Mollie\Api\Endpoints\PaymentEndpoint;
use Mollie\Api\Endpoints\PaymentRefundEndpoint;
use Mollie\Api\Endpoints\PermissionEndpoint;
use Mollie\Api\Endpoints\ProfileEndpoint;
use Mollie\Api\Endpoints\ProfileMethodEndpoint;
use Mollie\Api\Endpoints\RefundEndpoint;
use Mollie\Api\Endpoints\SettlementPaymentEndpoint;
use Mollie\Api\Endpoints\SettlementsEndpoint;
use Mollie\Api\Endpoints\ShipmentEndpoint;
use Mollie\Api\Endpoints\SubscriptionEndpoint;
use Mollie\Api\Endpoints\WalletEndpoint;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\Guzzle\RetryMiddlewareFactory;
use _PhpScopereac699eb1a3f\Psr\Http\Message\ResponseInterface;
use _PhpScopereac699eb1a3f\Psr\Http\Message\StreamInterface;
class MollieApiClient
{
    /**
     * Version of our client.
     */
    const CLIENT_VERSION = "2.31.1";
    /**
     * Endpoint of the remote API.
     */
    const API_ENDPOINT = "https://api.mollie.com";
    /**
     * Version of the remote API.
     */
    const API_VERSION = "v2";
    /**
     * HTTP Methods
     */
    const HTTP_GET = "GET";
    const HTTP_POST = "POST";
    const HTTP_DELETE = "DELETE";
    const HTTP_PATCH = "PATCH";
    /**
     * HTTP status codes
     */
    const HTTP_NO_CONTENT = 204;
    /**
     * Default response timeout (in seconds).
     */
    const TIMEOUT = 10;
    /**
     * Default connect timeout (in seconds).
     */
    const CONNECT_TIMEOUT = 2;
    /**
     * @var ClientInterface
     */
    protected $httpClient;
    /**
     * @var string
     */
    protected $apiEndpoint = self::API_ENDPOINT;
    /**
     * RESTful Payments resource.
     *
     * @var PaymentEndpoint
     */
    public $payments;
    /**
     * RESTful Methods resource.
     *
     * @var MethodEndpoint
     */
    public $methods;
    /**
     * @var ProfileMethodEndpoint
     */
    public $profileMethods;
    /**
     * RESTful Customers resource.
     *
     * @var CustomerEndpoint
     */
    public $customers;
    /**
     * RESTful Customer payments resource.
     *
     * @var CustomerPaymentsEndpoint
     */
    public $customerPayments;
    /**
     * RESTful Settlement resource.
     *
     * @var SettlementsEndpoint
     */
    public $settlements;
    /**
     * RESTful Settlement payment resource.
     *
     * @var \Mollie\Api\Endpoints\SettlementPaymentEndpoint
     */
    public $settlementPayments;
    /**
     * RESTful Subscription resource.
     *
     * @var SubscriptionEndpoint
     */
    public $subscriptions;
    /**
     * RESTful Mandate resource.
     *
     * @var MandateEndpoint
     */
    public $mandates;
    /**
     * @var ProfileEndpoint
     */
    public $profiles;
    /**
     * RESTful Organization resource.
     *
     * @var OrganizationEndpoint
     */
    public $organizations;
    /**
     * RESTful Permission resource.
     *
     * @var PermissionEndpoint
     */
    public $permissions;
    /**
     * RESTful Invoice resource.
     *
     * @var InvoiceEndpoint
     */
    public $invoices;
    /**
     * RESTful Onboarding resource.
     *
     * @var OnboardingEndpoint
     */
    public $onboarding;
    /**
     * RESTful Order resource.
     *
     * @var OrderEndpoint
     */
    public $orders;
    /**
     * RESTful OrderLine resource.
     *
     * @var OrderLineEndpoint
     */
    public $orderLines;
    /**
     * RESTful OrderPayment resource.
     *
     * @var OrderPaymentEndpoint
     */
    public $orderPayments;
    /**
     * RESTful Shipment resource.
     *
     * @var ShipmentEndpoint
     */
    public $shipments;
    /**
     * RESTful Refunds resource.
     *
     * @var RefundEndpoint
     */
    public $refunds;
    /**
     * RESTful Payment Refunds resource.
     *
     * @var PaymentRefundEndpoint
     */
    public $paymentRefunds;
    /**
     * RESTful Payment Captures resource.
     *
     * @var PaymentCaptureEndpoint
     */
    public $paymentCaptures;
    /**
     * RESTful Chargebacks resource.
     *
     * @var ChargebackEndpoint
     */
    public $chargebacks;
    /**
     * RESTful Payment Chargebacks resource.
     *
     * @var PaymentChargebackEndpoint
     */
    public $paymentChargebacks;
    /**
     * RESTful Order Refunds resource.
     *
     * @var OrderRefundEndpoint
     */
    public $orderRefunds;
    /**
     * Manages Wallet requests
     *
     * @var WalletEndpoint
     */
    public $wallets;
    /**
     * @var string
     */
    protected $apiKey;
    /**
     * True if an OAuth access token is set as API key.
     *
     * @var bool
     */
    protected $oauthAccess;
    /**
     * @var array
     */
    protected $versionStrings = [];
    /**
     * @var int
     */
    protected $lastHttpResponseStatusCode;
    /**
     * @param ClientInterface $httpClient
     *
     * @throws IncompatiblePlatform
     */
    public function __construct(\_PhpScopereac699eb1a3f\GuzzleHttp\ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
        if (!$this->httpClient) {
            $retryMiddlewareFactory = new \Mollie\Api\Guzzle\RetryMiddlewareFactory();
            $handlerStack = \_PhpScopereac699eb1a3f\GuzzleHttp\HandlerStack::create();
            $handlerStack->push($retryMiddlewareFactory->retry());
            $this->httpClient = new \_PhpScopereac699eb1a3f\GuzzleHttp\Client([\_PhpScopereac699eb1a3f\GuzzleHttp\RequestOptions::VERIFY => \_PhpScopereac699eb1a3f\Composer\CaBundle\CaBundle::getBundledCaBundlePath(), \_PhpScopereac699eb1a3f\GuzzleHttp\RequestOptions::TIMEOUT => self::TIMEOUT, \_PhpScopereac699eb1a3f\GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT, 'handler' => $handlerStack]);
        }
        $compatibilityChecker = new \Mollie\Api\CompatibilityChecker();
        $compatibilityChecker->checkCompatibility();
        $this->initializeEndpoints();
        $this->addVersionString("Mollie/" . self::CLIENT_VERSION);
        $this->addVersionString("PHP/" . \phpversion());
        if (\defined('\\GuzzleHttp\\ClientInterface::MAJOR_VERSION')) {
            // Guzzle 7
            $this->addVersionString("Guzzle/" . \_PhpScopereac699eb1a3f\GuzzleHttp\ClientInterface::MAJOR_VERSION);
        } elseif (\defined('\\GuzzleHttp\\ClientInterface::VERSION')) {
            // Before Guzzle 7
            $this->addVersionString("Guzzle/" . \_PhpScopereac699eb1a3f\GuzzleHttp\ClientInterface::VERSION);
        }
    }
    public function initializeEndpoints()
    {
        $this->payments = new \Mollie\Api\Endpoints\PaymentEndpoint($this);
        $this->methods = new \Mollie\Api\Endpoints\MethodEndpoint($this);
        $this->profileMethods = new \Mollie\Api\Endpoints\ProfileMethodEndpoint($this);
        $this->customers = new \Mollie\Api\Endpoints\CustomerEndpoint($this);
        $this->settlements = new \Mollie\Api\Endpoints\SettlementsEndpoint($this);
        $this->settlementPayments = new \Mollie\Api\Endpoints\SettlementPaymentEndpoint($this);
        $this->subscriptions = new \Mollie\Api\Endpoints\SubscriptionEndpoint($this);
        $this->customerPayments = new \Mollie\Api\Endpoints\CustomerPaymentsEndpoint($this);
        $this->mandates = new \Mollie\Api\Endpoints\MandateEndpoint($this);
        $this->invoices = new \Mollie\Api\Endpoints\InvoiceEndpoint($this);
        $this->permissions = new \Mollie\Api\Endpoints\PermissionEndpoint($this);
        $this->profiles = new \Mollie\Api\Endpoints\ProfileEndpoint($this);
        $this->onboarding = new \Mollie\Api\Endpoints\OnboardingEndpoint($this);
        $this->organizations = new \Mollie\Api\Endpoints\OrganizationEndpoint($this);
        $this->orders = new \Mollie\Api\Endpoints\OrderEndpoint($this);
        $this->orderLines = new \Mollie\Api\Endpoints\OrderLineEndpoint($this);
        $this->orderPayments = new \Mollie\Api\Endpoints\OrderPaymentEndpoint($this);
        $this->orderRefunds = new \Mollie\Api\Endpoints\OrderRefundEndpoint($this);
        $this->shipments = new \Mollie\Api\Endpoints\ShipmentEndpoint($this);
        $this->refunds = new \Mollie\Api\Endpoints\RefundEndpoint($this);
        $this->paymentRefunds = new \Mollie\Api\Endpoints\PaymentRefundEndpoint($this);
        $this->paymentCaptures = new \Mollie\Api\Endpoints\PaymentCaptureEndpoint($this);
        $this->chargebacks = new \Mollie\Api\Endpoints\ChargebackEndpoint($this);
        $this->paymentChargebacks = new \Mollie\Api\Endpoints\PaymentChargebackEndpoint($this);
        $this->wallets = new \Mollie\Api\Endpoints\WalletEndpoint($this);
    }
    /**
     * @param string $url
     *
     * @return MollieApiClient
     */
    public function setApiEndpoint($url)
    {
        $this->apiEndpoint = \rtrim(\trim($url), '/');
        return $this;
    }
    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
    /**
     * @param string $apiKey The Mollie API key, starting with 'test_' or 'live_'
     *
     * @return MollieApiClient
     * @throws ApiException
     */
    public function setApiKey($apiKey)
    {
        $apiKey = \trim($apiKey);
        if (!\preg_match('/^(live|test)_\\w{30,}$/', $apiKey)) {
            throw new \Mollie\Api\Exceptions\ApiException("Invalid API key: '{$apiKey}'. An API key must start with 'test_' or 'live_' and must be at least 30 characters long.");
        }
        $this->apiKey = $apiKey;
        $this->oauthAccess = \false;
        return $this;
    }
    /**
     * @param string $accessToken OAuth access token, starting with 'access_'
     *
     * @return MollieApiClient
     * @throws ApiException
     */
    public function setAccessToken($accessToken)
    {
        $accessToken = \trim($accessToken);
        if (!\preg_match('/^access_\\w+$/', $accessToken)) {
            throw new \Mollie\Api\Exceptions\ApiException("Invalid OAuth access token: '{$accessToken}'. An access token must start with 'access_'.");
        }
        $this->apiKey = $accessToken;
        $this->oauthAccess = \true;
        return $this;
    }
    /**
     * Returns null if no API key has been set yet.
     *
     * @return bool|null
     */
    public function usesOAuth()
    {
        return $this->oauthAccess;
    }
    /**
     * @param string $versionString
     *
     * @return MollieApiClient
     */
    public function addVersionString($versionString)
    {
        $this->versionStrings[] = \str_replace([" ", "\t", "\n", "\r"], '-', $versionString);
        return $this;
    }
    /**
     * Perform an http call. This method is used by the resource specific classes. Please use the $payments property to
     * perform operations on payments.
     *
     * @param string $httpMethod
     * @param string $apiMethod
     * @param string|null|resource|StreamInterface $httpBody
     *
     * @return \stdClass
     * @throws ApiException
     *
     * @codeCoverageIgnore
     */
    public function performHttpCall($httpMethod, $apiMethod, $httpBody = null)
    {
        $url = $this->apiEndpoint . "/" . self::API_VERSION . "/" . $apiMethod;
        return $this->performHttpCallToFullUrl($httpMethod, $url, $httpBody);
    }
    /**
     * Perform an http call to a full url. This method is used by the resource specific classes.
     *
     * @see $payments
     * @see $isuers
     *
     * @param string $httpMethod
     * @param string $url
     * @param string|null|resource|StreamInterface $httpBody
     *
     * @return \stdClass|null
     * @throws ApiException
     *
     * @codeCoverageIgnore
     */
    public function performHttpCallToFullUrl($httpMethod, $url, $httpBody = null)
    {
        if (empty($this->apiKey)) {
            throw new \Mollie\Api\Exceptions\ApiException("You have not set an API key or OAuth access token. Please use setApiKey() to set the API key.");
        }
        $userAgent = \implode(' ', $this->versionStrings);
        if ($this->usesOAuth()) {
            $userAgent .= " OAuth/2.0";
        }
        $headers = ['Accept' => "application/json", 'Authorization' => "Bearer {$this->apiKey}", 'User-Agent' => $userAgent];
        if (\function_exists("php_uname")) {
            $headers['X-Mollie-Client-Info'] = \php_uname();
        }
        $request = new \_PhpScopereac699eb1a3f\GuzzleHttp\Psr7\Request($httpMethod, $url, $headers, $httpBody);
        try {
            $response = $this->httpClient->send($request, ['http_errors' => \false]);
        } catch (\_PhpScopereac699eb1a3f\GuzzleHttp\Exception\GuzzleException $e) {
            throw \Mollie\Api\Exceptions\ApiException::createFromGuzzleException($e, $request);
        }
        if (!$response) {
            throw new \Mollie\Api\Exceptions\ApiException("Did not receive API response.", 0, null, $request);
        }
        return $this->parseResponseBody($response);
    }
    /**
     * Parse the PSR-7 Response body
     *
     * @param ResponseInterface $response
     * @return \stdClass|null
     * @throws ApiException
     */
    private function parseResponseBody(\_PhpScopereac699eb1a3f\Psr\Http\Message\ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        if (empty($body)) {
            if ($response->getStatusCode() === self::HTTP_NO_CONTENT) {
                return null;
            }
            throw new \Mollie\Api\Exceptions\ApiException("No response body found.");
        }
        $object = @\json_decode($body);
        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new \Mollie\Api\Exceptions\ApiException("Unable to decode Mollie response: '{$body}'.");
        }
        if ($response->getStatusCode() >= 400) {
            throw \Mollie\Api\Exceptions\ApiException::createFromResponse($response, null);
        }
        return $object;
    }
    /**
     * Serialization can be used for caching. Of course doing so can be dangerous but some like to live dangerously.
     *
     * \serialize() should be called on the collections or object you want to cache.
     *
     * We don't need any property that can be set by the constructor, only properties that are set by setters.
     *
     * Note that the API key is not serialized, so you need to set the key again after unserializing if you want to do
     * more API calls.
     *
     * @deprecated
     * @return string[]
     */
    public function __sleep()
    {
        return ["apiEndpoint"];
    }
    /**
     * When unserializing a collection or a resource, this class should restore itself.
     *
     * Note that if you use a custom GuzzleClient, this client is lost. You can't re set the Client, so you should
     * probably not use this feature.
     *
     * @throws IncompatiblePlatform If suddenly unserialized on an incompatible platform.
     */
    public function __wakeup()
    {
        $this->__construct();
    }
}
