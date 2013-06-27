<?php
namespace Dropbox;

/**
 * Information about how you've registered your application with the Dropbox API.
 */
final class AppInfo
{
    /**
     * Your Dropbox <em>app key</em> (OAuth calls this the <em>consumer key</em>).  You can
     * create an app key and secret on the <a href="http://dropbox.com/developers/apps">Dropbox developer website</a>.
     *
     * @return string
     */
    function getKey() { return $this->key; }

    /** @var string */
    private $key;

    /**
     * Your Dropbox <em>app secret</em> (OAuth calls this the <em>consumer secret</em>).  You can
     * create an app key and secret on the <a href="http://dropbox.com/developers/apps">Dropbox developer website</a>.
     *
     * Make sure that this is kept a secret.  Someone with your app secret can impesonate your
     * application.  People sometimes ask for help on the Dropbox API forums and
     * copy/paste code that includes their app secret.  Do not do that.
     *
     * @return string
     */
    function getSecret() { return $this->secret; }

    /** @var string */
    private $secret;

    /**
     * The type of access your app is registered for.  You can see how your apps areregistered
     * on the <a href="http://dropbox.com/developers/apps">Dropbox developer website</a>.
     *
     * @return AccessType
     */
    function getAccessType() { return $this->accessType; }

    /** @var string */
    private $accessType;

    /**
     * The set of servers your app will use.  This defaults to the standard Dropbox servers
     * {@link Host::getDefault}.
     *
     * @return Host
     *
     * @internal
     */
    function getHost() { return $this->host; }

    /** @var Host */
    private $host;

    /**
     * Constructor.
     *
     * @param string $key
     *    See {@link getKey()}
     * @param string $secret
     *    See {@link getSecret()}
     * @param string $accessType
     *    See {@link getAccessType()}
     */
    function __construct($key, $secret, $accessType)
    {
        Token::checkKeyArg($key);
        Token::checkSecretArg($secret);
        AccessType::checkArg("accessType", $accessType);

        $this->key = $key;
        $this->secret = $secret;
        $this->accessType = $accessType;

        // The $host parameter is sort of internal.  We don't include it in the param list because
        // we don't want it to be included in the documentation.  Use PHP arg list hacks to get at
        // it.
        $host = null;
        if (\func_num_args() == 4) {
            $host = \func_get_arg(3);
            Host::checkArgOrNull("host", $host);
        }
        if ($host === null) {
            $host = Host::getDefault();
        }
        $this->host = $host;
    }

    /**
     * Loads a JSON file containing information about your app. At a minimum, the file must include
     * the key, secret, and access_type fields.  Run 'php authorize.php' in the examples directory
     * for details about what this file should look like.
     *
     * @param string $path Path to a JSON file
     * @return AppInfo
     */
    static function loadFromJsonFile($path)
    {
        list($rawJson, $appInfo) = self::loadFromJsonFileWithRaw($path);
        return $appInfo;
    }

    /**
     * Loads a JSON file containing information about your app. At a minimum, the file must include
     * the key, secret, and access_type fields.  Run 'php authorize.php' in the examples directory
     * for details about what this file should look like.
     *
     * @param string $path Path to a JSON file
     *
     * @return array
     *    A list of two items.  The first is a PHP array representation of the raw JSON, the second
     *    is an AppInfo object that is the parsed version of the JSON.
     *
     * @internal
     */
    static function loadFromJsonFileWithRaw($path)
    {
        if (!file_exists($path)) {
            throw new AppInfoLoadException("File doesn't exist: \"$path\"");
        }

        $str = file_get_contents($path);
        $jsonArr = json_decode($str, TRUE);

        if (is_null($jsonArr)) {
            throw new AppInfoLoadException("JSON parse error: \"$path\"");
        }

        $appInfo = self::loadFromJson($jsonArr);

        return array($jsonArr, $appInfo);
    }

    /**
     * Parses a JSON object to build an AppInfo object.  If you would like to load this from a file,
     * use the loadFromJsonFile() method.
     *
     * @param array $jsonArr Output from json_decode($str, TRUE)
     *
     * @return AppInfo
     *
     * @throws AppInfoLoadException
     */
    static function loadFromJson($jsonArr)
    {
        if (!is_array($jsonArr)) {
            throw new AppInfoLoadException("Expecting JSON object, got something else");
        }

        $requiredKeys = array("key", "secret", "access_type");
        foreach ($requiredKeys as $key) {
            if (!isset($jsonArr[$key])) {
                throw new AppInfoLoadException("Missing field \"$key\"");
            }

            if (!is_string($jsonArr[$key])) {
                throw new AppInfoLoadException("Expecting field \"$key\" to be a string");
            }
        }

        // Check app_key and app_secret
        $appKey = $jsonArr["key"];
        $appSecret = $jsonArr["secret"];

        $tokenErr = Token::getTokenPartError($appKey);
        if (!is_null($tokenErr)) {
            throw new AppInfoLoadException("Field \"key\" doesn't look like a valid app key: $tokenErr");
        }

        $tokenErr = Token::getTokenPartError($appSecret);
        if (!is_null($tokenErr)) {
            throw new AppInfoLoadException("Field \"secret\" doesn't look like a valid app secret: $tokenErr");
        }

        // Check the access type
        $accessTypeStr = $jsonArr["access_type"];
        if ($accessTypeStr === "FullDropbox") {
            $accessType = AccessType::FullDropbox();
        }
        else if ($accessTypeStr === "AppFolder") {
            $accessType = AccessType::AppFolder();
        }
        else {
            throw new AppInfoLoadException("Field \"access_type\" must be either \"FullDropbox\" or \"AppFolder\"");
        }

        // Check for the optional 'host' field
        if (!isset($jsonArr["host"])) {
            $host = Host::getDefault();
        }
        else {
            $baseHost = $jsonArr["host"];
            if (!is_string($baseHost)) {
                throw new AppInfoLoadException("Optional field \"host\" must be a string");
            }

            $api = "api-$baseHost";
            $content = "api-content-$baseHost";
            $web = "meta-$baseHost";

            $host = new Host($api, $content, $web);
        }

        return new AppInfo($appKey, $appSecret, $accessType, $host);
    }

    /**
     * Use this to check that a function argument is of type <code>AppInfo</code>
     *
     * @internal
     */
    static function checkArg($argName, $argValue)
    {
        if (!($argValue instanceof self)) Checker::throwError($argName, $argValue, __CLASS__);
    }

    /**
     * Use this to check that a function argument is either <code>null</code> or of type
     * <code>AppInfo</code>.
     *
     * @internal
     */
    static function checkArgOrNull($argName, $argValue)
    {
        if ($argValue === null) return;
        if (!($argValue instanceof self)) Checker::throwError($argName, $argValue, __CLASS__);
    }
}
