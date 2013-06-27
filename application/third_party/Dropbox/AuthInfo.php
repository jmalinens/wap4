<?php
namespace Dropbox;

/**
 * This class contains methods to load an AppInfo and AccessToken from a JSON file.
 * This can help simplify simple scripts (such as the example programs that come with the
 * SDK) but is probably not useful in typical Dropbox API apps.
 *
 */
final class AuthInfo
{
    /**
     * Loads a JSON file containing authorization information for your app. 'php authorize.php'
     * in the examples directory for details about what this file should look like.
     *
     * @param string $path
     *    Path to a JSON file
     * @return array
     *    A pair of (AppInfo $appInfo, AccessToken $accessToken).
     *
     * @throws AuthInfoLoadException
     */
    static function loadFromJsonFile($path)
    {
        if (!file_exists($path)) {
            throw new AuthInfoLoadException("File doesn't exist: \"$path\"");
        }

        $str = file_get_contents($path);
        $jsonArr = json_decode($str, TRUE);

        if (is_null($jsonArr)) {
            throw new AuthInfoLoadException("JSON parse error: \"$path\"");
        }

        return self::loadFromJson($jsonArr);
    }

    /**
     * Parses a JSON object to build an AuthInfo object.  If you would like to load this from a file,
     * please use the @see loadFromJsonFile method.
     *
     * @param array $jsonArr
     *    A parsed JSON object, typcally the result of json_decode(..., TRUE).
     * @return array
     *    A list(AppInfo $appInfo, AccessToken $accessToken).
     *
     * @throws AuthInfoLoadException
     */
    private static function loadFromJson($jsonArr)
    {
        if (!is_array($jsonArr)) {
            throw new AuthInfoLoadException("Expecting JSON object, found something else");
        }

        if (!isset($jsonArr['app'])) {
            throw new AuthInfoLoadException("Missing field \"app\"");
        }

        // Extract app info
        $appJson = $jsonArr['app'];

        try {
            $appInfo = AppInfo::loadFromJson($appJson);
        }
        catch (AppInfoLoadException $e) {
            throw new AuthInfoLoadException("Bad \"app\" field: ".$e->getMessage());
        }

        // Extract access token
        if (!isset($jsonArr['access_token'])) {
            throw new AuthInfoLoadException("Missing field \"access_token\"");
        }

        $accessTokenString = $jsonArr['access_token'];
        if (!is_string($accessTokenString)) {
            throw new AuthInfoLoadException("Expecting field \"access_token\" to be a string");
        }

        $accessToken = AccessToken::deserialize($accessTokenString);

        return array($appInfo, $accessToken);
    }
}
