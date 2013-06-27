<?php
namespace Dropbox;

/**
 * Configure how you plan to connect to the Dropbox API (your app information, your user's
 * locale, etc).
 */
final class Config
{
    /**
     * Whatever AppInfo was passed into the constructor.
     *
     * @return AppInfo
     */
    function getAppInfo() { return $this->appInfo; }

    /** @var AppInfo */
    private $appInfo;

    /**
     * An identifier for the API client, typically of the form "Name/Version".
     * This is used to set the HTTP <code>User-Agent</code> header when making API requests.
     * Example: <code>"PhotoEditServer/1.3"</code>
     *
     * If you're the author a higher-level library on top of the basic SDK, and the
     * "Photo Edit" app's server code is using your library to access Dropbox, you should append
     * your library's name and version to form the full identifier.  For example,
     * if your library is called "File Picker", you might set this field to:
     * <code>"PhotoEditServer/1.3 FilePicker/0.1-beta"</code>
     *
     * The exact format of the <code>User-Agent</code> header is described in
     * <a href="http://tools.ietf.org/html/rfc2616#section-3.8">section 3.8 of the HTTP specification</a>.
     *
     * Note that underlying HTTP client may append other things to the <code>User-Agent</code>, such as
     * the name of the library being used to actually make the HTTP request (such as cURL).
     *
     * @return string
     */
    function getClientIdentifier() { return $this->clientIdentifier; }

    /** @var string */
    private $clientIdentifier;

    private static $dropboxSupportedLocales = array('en', 'de', 'fr', 'es', 'jp');

    /**
     * Given a locale string, returns the closest supported locale that the Dropbox servers
     * support.  You can then use that locale string as an argument to the constructor.
     *
     * If you omit the $locale argument (or pass in null), we'll try using the default locale
     * from {@link Locale::getDefault()}.
     *
     * @param null|string $locale
     * @return string
     */
    static function getClosestSupportedLocale($locale = null)
    {
        if ($locale === null) {
            $locale = setlocale(LC_ALL, 0);
            if ($locale === false) return "en";
        }

        $parts = preg_split('[-_]', $locale, 1);
        $languageOnly = strtolower($parts[0]);

        if (in_array($languageOnly, self::$dropboxSupportedLocales)) {
            return $languageOnly;
        } else {
            return "en";
        }
    }

    /**
     * The locale of the user of your application.  Some API calls return localized
     * data and error messages; this "user locale" setting determines which locale
     * the server should use to localize those strings.
     *
     * @return string
     */
    function getUserLocale() { return $this->userLocale; }

    /** @var string */
    private $userLocale;

    /**
     * Constructor.
     *
     * @param AppInfo $appInfo
     *     See {@link getAppInfo()}
     * @param string $clientIdentifier
     *     See {@link getClientIdentifier()}
     * @param null|string $userLocale
     *     See {@link getUseLocale()}
     */
    function __construct($appInfo, $clientIdentifier, $userLocale = null)
    {
        AppInfo::checkArg("appInfo", $appInfo);
        Checker::argStringNonEmpty("clientIdentifier", $clientIdentifier);
        Checker::argStringNonEmptyOrNull("userLocale", $userLocale);

        $this->appInfo = $appInfo;
        $this->clientIdentifier = $clientIdentifier;
        $this->userLocale = self::getClosestSupportedLocale($userLocale);
    }

    /**
     * Check that a function argument is of type <code>Config</code>.
     *
     * @internal
     */
    static function checkArg($argName, $argValue)
    {
        if (!($argValue instanceof self)) Checker::throwError($argName, $argValue, __CLASS__);
    }

    /**
     * Use this to check that a function argument is either <code>null</code> or of type
     * <code>Config</code>.
     *
     * @internal
     */
    static function checkArgOrNull($argName, $argValue)
    {
        if ($argValue === null) return;
        if (!($argValue instanceof self)) Checker::throwError($argName, $argValue, __CLASS__);
    }
}
