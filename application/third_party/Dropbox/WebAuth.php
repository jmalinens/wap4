<?php
namespace Dropbox;

/**
 * Use {@link WebAuth::start()} and {@link WebAuth::finish()} to guide your
 * user through the process of giving your app access to their Dropbox account.  At the end, you
 * will have a {@link AccessToken}, which you can pass to {@link Client} and start making
 * API calls.
 *
 * This class is stateless so it can be shared/reused.
 */
final class WebAuth
{
    /**
     * The config used when making requests to the Dropbox server.
     *
     * @return Config
     */
    function getConfig() { return $this->config; }

    /** @var Config */
    private $config;

    /**
     * Constructor.
     *
     * @param Config $config
     *     See {@link getConfig()}
     */
    function __construct($config)
    {
        Config::checkArg("config", $config);
        $this->config = $config;
    }

    /**
     * Tells Dropbox that you want to start authorization and returns the information necessary
     * to continue authorization.  This corresponds to step 1 of the three-step OAuth web flow.
     *
     * After this function returns, direct your user to the returned <code>$authorizeUrl</code>,
     * which gives them a chance to grant your application access to their Dropbox account.  This
     * corresponds to step 2 of the three-step OAuth web flow.
     *
     * If they choose to grant access, they will be redirected to the URL you provide for
     * <code>$callbackUrl</code>, after which you should call {@link finish()} to get an
     * access token.
     *
     * <code>
     * use \Dropbox as dbx;
     * $config = new dbx\Config(...);
     * $webAuth = new dbx\WebAuth($config);
     * $callbackUrl = "https://example.org/dropbox-auth-finish";
     * list($requestToken, $authorizeUrl) = $webAuth->start($callbackUrl);
     * $_SESSION['dropbox-request-token'] = $requestToken->serialize();
     * header("Location: $authorizeUrl");
     * </code>
     *
     * @param string $callbackUrl
     *    The URL that the Dropbox servers will redirect the user to after the user finishes
     *    authorizing your app.  If this is <code>null</code>, the user will not be redirected.
     *
     * @return array
     *    A <code>list(RequestToken $requestToken, string $authorizeUrl)</code>.  Redirect the
     *    user's browser to <code>$authorizeUrl</code>.  When they're done authorizing, call
     *    {@link finish()} with <code>$requestToken</code>.
     *
     * @throws Exception
     */
    function start($callbackUrl)
    {
        Checker::argStringOrNull("callbackUrl", $callbackUrl);

        $url = RequestUtil::buildUri(
            $this->config->getAppInfo()->getHost()->getApi(),
            "1/oauth/request_token");

        $params = array(
            "oauth_signature_method" => "PLAINTEXT",
            "oauth_consumer_key" => $this->config->getAppInfo()->getKey(),
            "oauth_signature" => rawurlencode($this->config->getAppInfo()->getSecret()) . "&",
            "locale" => $this->config->getUserLocale(),
        );

        $curl = RequestUtil::mkCurlWithoutAuth($this->config, $url);
        $curl->set(CURLOPT_POST, true);
        $curl->set(CURLOPT_POSTFIELDS, RequestUtil::buildPostBody($params));

        $curl->set(CURLOPT_RETURNTRANSFER, true);
        $response = $curl->exec();

        if ($response->statusCode !== 200) throw RequestUtil::unexpectedStatus($response);

        $parts = array();
        parse_str($response->body, $parts);
        if (!array_key_exists('oauth_token', $parts)) {
            throw new Exception_BadResponse("Missing \"oauth_token\" parameter.");
        }
        if (!array_key_exists('oauth_token_secret', $parts)) {
            throw new Exception_BadResponse("Missing \"oauth_token_secret\" parameter.");
        }
        $requestToken = new RequestToken($parts['oauth_token'], $parts['oauth_token_secret']);

        $authorizeUrl = RequestUtil::buildUrl(
            $this->config,
            $this->config->getAppInfo()->getHost()->getWeb(),
            "1/oauth/authorize",
            array(
                "oauth_token" => $requestToken->getKey(),
                "oauth_callback" => $callbackUrl,
            ));

        return array($requestToken, $authorizeUrl);
    }

    /**
     * Call this after the user has visited the authorize URL
     * (returned by {@link start()}) and approved your app.  This corresponds to
     * step 3 of the three-step OAuth web flow.
     *
     * Example (see {@link start() for first part):
     * <code>
     * // In the request handler for "/dropbox-auth-finish"
     * $givenKey = $_GET['oauth_token'];
     * $requestToken = RequestToken::deserialize($_SESSION['dropbox-request-token'])
     * if (!$requestToken->matchesKey($givenKey)) {
     *     // Do not continue.  You could show an error or redirect back to
     *     // the beginning of the authorization process.
     * }
     * $webAuth = new dbx\WebAuth($config);
     * list($accessToken, $dropboxUserId) = $webAuth->finish($requestToken);
     * saveDropboxAccessTokenInYourDatabase($accessToken->serialize());
     * </code>
     *
     * @param RequestToken $requestToken
     *    The <code>RequestToken</code> returned by {@link start()}.
     *
     * @return array
     *    A <code>list(RequestToken $requestToken, string $dropboxUserId)</code>.  Use
     *    <code>$requestToken</code> to construct a {@link Client} object and start making
     *    API calls.  <code>$dropboxUserId</code> is the user ID of the user's Dropbox
     *    account and is for your own reference.
     *
     * @throws Exception
     */
    function finish($requestToken)
    {
        RequestToken::checkArg("requestToken", $requestToken);

        $response = RequestUtil::doPost(
            $this->config,
            $requestToken,
            $this->config->getAppInfo()->getHost()->getApi(),
            "1/oauth/access_token");

        if ($response->statusCode !== 200) throw RequestUtil::unexpectedStatus($response);

        $parts = array();
        parse_str($response->body, $parts);
        if (!array_key_exists('oauth_token', $parts)) {
            throw new Exception_BadResponse("Missing \"oauth_token\" parameter.");
        }
        if (!array_key_exists('oauth_token_secret', $parts)) {
            throw new Exception_BadResponse("Missing \"oauth_token_secret\" parameter.");
        }
        if (!array_key_exists('uid', $parts)) {
            throw new Exception_BadResponse("Missing \"uid\" parameter.");
        }

        $accessToken = new AccessToken($parts['oauth_token'], $parts['oauth_token_secret']);
        return array($accessToken, $parts['uid']);
    }
}
