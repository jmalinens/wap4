<?php
namespace Dropbox;

if (!function_exists('curl_init')) {
    throw new \Exception("The Dropbox SDK requires the cURL PHP extension, but it looks like you don't have it (couldn't find function \"curl_init\").  Library: \"" . __FILE__ . "\".");
}

if (!function_exists('json_decode')) {
    throw new \Exception("The Dropbox SDK requires the JSON PHP extension, but it looks like you don't have it (couldn't find function \"json_decode\").  Library: \"" . __FILE__ . "\".");
}

if (strlen((string) PHP_INT_MAX) < 19) {
    // Looks like we're running on a 32-bit build of PHP.  This could cause problems because some of the numbers
    // we use (file sizes, quota, etc) can be larger than 32-bit ints can handle.
    throw new \Exception("The Dropbox SDK at least a 64-bit build of PHP, but it looks like we're running a 32-bit build (PHP_INT_MAX=" . ((string) PHP_INT_MAX) . ").  Library: \"" . __FILE__ . "\"");
}

/**
 * @internal
 */
final class RequestUtil
{
    /**
     * @param Config $config
     * @param string $host
     * @param string $path
     * @param array $params
     * @return string
     */
    static function buildUrl($config, $host, $path, $params = null)
    {
        $url = self::buildUri($host, $path);
        $url .= "?locale=" . rawurlencode($config->getUserLocale());

        if ($params !== null) {
            foreach ($params as $key => $value) {
                Checker::argStringNonEmpty("key in 'params'", $key);
                if ($value !== null) {
                    if (is_bool($value)) {
                        $value = $value ? "true" : "false";
                    }
                    else if (is_int($value)) {
                        $value = (string) $value;
                    }
                    else if (!is_string($value)) {
                        throw new \InvalidArgumentException("params['$key'] is not a string, int, or bool");
                    }
                    $url .= "&" . rawurlencode($key) . "=" . rawurlencode($value);
                }
            }
        }
        return $url;
    }

    /**
     * @param string $host
     * @param string $path
     * @return string
     */
    static function buildUri($host, $path)
    {
        Checker::argStringNonEmpty("host", $host);
        Checker::argStringNonEmpty("path", $path);
        return "https://" . $host . "/" . $path;
    }

    /**
     * @param AppInfo $appInfo
     * @param Token $token
     * @return string
     */
    private static function buildOAuthHeader($appInfo, $token)
    {
        return "OAuth oauth_signature_method=\"PLAINTEXT\""
             . ", oauth_consumer_key=\"" . rawurlencode($appInfo->getKey()) . "\""
             . ", oauth_token=\"" . rawurlencode($token->getKey()) . "\""
             . ", oauth_signature=\"" . rawurlencode($appInfo->getSecret()) . "&" . rawurlencode($token->getSecret()) . "\"";
    }

    /**
     * @param Config $config
     * @param string $url
     * @return Curl
     */
    static function mkCurlWithoutAuth($config, $url)
    {
        $curl = new Curl($url);

        $curl->set(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->set(CURLOPT_TIMEOUT, 60);

        //$curl->set(CURLOPT_VERBOSE, true);  // For debugging.
        $curl->addHeader("User-Agent: ".$config->getClientIdentifier()." Dropbox-PHP-SDK");

        return $curl;
    }

    /**
     * @param Config $config
     * @param string $url
     * @param Token $authToken
     * @return Curl
     */
    static function mkCurl($config, $url, $authToken)
    {
        $curl = self::mkCurlWithoutAuth($config, $url);
        $curl->addHeader("Authorization: ".self::buildOAuthHeader($config->getAppInfo(), $authToken));
        return $curl;
    }

    static function buildPostBody($params)
    {
        if ($params === null) return "";

        $pairs = array();
        foreach ($params as $key => $value) {
            Checker::argStringNonEmpty("key in 'params'", $key);
            if ($value !== null) {
                if (is_bool($value)) {
                    $value = $value ? "true" : "false";
                }
                else if (is_int($value)) {
                    $value = (string) $value;
                }
                else if (!is_string($value)) {
                    throw new \InvalidArgumentException("params['$key'] is not a string, int, or bool");
                }
                $pairs[] = rawurlencode($key) . "=" . rawurlencode((string) $value);
            }
        }
        return implode("&", $pairs);
    }

    /**
     * @param Config $config
     * @param Token $oauthToken
     * @param Curl $curl
     */
    static function addAuthHeader($config, $oauthToken, $curl)
    {
        $curl->addHeader("Authorization: ".self::buildOAuthHeader($config->getAppInfo(), $oauthToken));
    }

    /**
     * @param Config $config
     * @param Token $authToken
     * @param string $host
     * @param string $path
     * @param array|null $params
     *
     * @return HttpResponse
     *
     * @throws Exception
     */
    static function doPost($config, $authToken, $host, $path, $params = null)
    {
        Config::checkArg("config", $config);
        Token::checkArg("authToken", $authToken);
        Checker::argStringNonEmpty("host", $host);
        Checker::argStringNonEmpty("path", $path);

        $url = self::buildUri($host, $path);

        if ($params === null) $params = array();
        $params['locale'] = $config->getUserLocale();

        $curl = self::mkCurl($config, $url, $authToken);
        $curl->set(CURLOPT_POST, true);
        $curl->set(CURLOPT_POSTFIELDS, self::buildPostBody($params));

        $curl->set(CURLOPT_RETURNTRANSFER, true);
        return $curl->exec();
    }

    /**
     * @param Config $config
     * @param Token $authToken
     * @param string $host
     * @param string $path
     * @param array|null $params
     *
     * @return HttpResponse
     *
     * @throws Exception
     */
    static function doGet($config, $authToken, $host, $path, $params = null)
    {
        $url = self::buildUrl($config, $host, $path, $params);

        $curl = self::mkCurl($config, $url, $authToken);
        $curl->set(CURLOPT_HTTPGET, true);
        $curl->set(CURLOPT_RETURNTRANSFER, true);

        return $curl->exec();
    }

    /**
     * @param string $responseBody
     * @return mixed
     * @throws Exception_BadResponse
     */
    static function parseResponseJson($responseBody)
    {
        $obj = json_decode($responseBody, TRUE, 10);
        if ($obj === null) {
            throw new Exception_BadResponse("Got bad JSON from server: $responseBody");
        }
        return $obj;
    }

    static function unexpectedStatus($httpResponse)
    {
        $message = "HTTP status ".$httpResponse->statusCode;
        if (is_string($httpResponse->body)) {
            // TODO: Maybe only include the first ~200 chars of the body?
            $message = "\n".$httpResponse->body;
        }

        $sc = $httpResponse->statusCode;

        if ($sc === 400) return new Exception_BadRequest($message);
        if ($sc === 401) return new Exception_InvalidAccessToken($message);
        if ($sc === 500) return new Exception_ServerError($message);
        if ($sc === 503) return new Exception_RetryLater($message);

        return new Exception_BadResponse("unexpected HTTP status code: $sc: $message");
    }

    /**
     * @param int $maxRetries
     *    The number of times to retry it the action if it fails with one of the transient
     *    API errors.  A value of 1 means we'll try the action once and if it fails, we
     *    will retry once.
     *
     * @param callable $action
     *    The the action you want to retry.
     *
     * @return mixed
     *    Whatever is returned by the $action callable.
     */
    static function runWithRetry($maxRetries, $action)
    {
        Checker::argNat("maxRetries", $maxRetries);

        $retryDelay = 1;
        $numRetries = 0;
        while (true) {
            try {
                return $action();
            }
            // These exception types are the ones we think are possibly transient errors.
            catch (Exception_NetworkIO $ex) {
                $savedEx = $ex;
            }
            catch (Exception_ServerError $ex) {
                $savedEx = $ex;
            }
            catch (Exception_RetryLater $ex) {
                $savedEx = $ex;
            }

            // We maxed out our retries.  Propagate the last exception we got.
            if ($numRetries >= $maxRetries) throw $savedEx;

            $numRetries++;
            sleep($retryDelay);
            $retryDelay *= 2;  // Exponential back-off.
        }
        throw new \RuntimeException("unreachable");
    }

    static function secureStringEquals($a, $b)
    {
        Checker::argString("a", $a);
        Checker::argString("b", $b);
        if (strlen($a) !== strlen($b)) return false;
        $result = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $result === 0;
    }
}
