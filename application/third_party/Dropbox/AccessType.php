<?php
namespace Dropbox;

/**
 * An enum for the two categories of Dropbox API apps: {@link FullDropbox} and
 * {@link AppFolder}.
 */
final class AccessType
{
    /**
     * Some Dropbox API HTTP endpoints require the app access type to be embedded in the URL.
     * This field holds the exact string that needs to be embedded in the URL.
     *
     * @var string
     */
    private $urlPart;

    /**
     * @param string $urlPart
     */
    private function __construct($urlPart)
    {
        $this->urlPart = $urlPart;
    }

    // Shared instances.
    /** @var AccessType */
    private static $fullDropbox;
    /** @var AccessType */
    private static $appFolder;

    /**
     * Returns the "full Dropbox" access type, which is for apps that want access to
     * a user's entire Dropbox.
     *
     * @return AccessType
     */
    static function FullDropbox()
    {
        if (self::$fullDropbox === null) self::$fullDropbox = new AccessType("dropbox");
        return self::$fullDropbox;
    }

    /**
     * Returns the "app folder" access type, which is for apps only access their own
     * app-specific folder within the user's Dropbox.
     *
     * @return AccessType
     */
    static function AppFolder()
    {
        if (self::$appFolder === null) self::$appFolder = new AccessType("sandbox");
        return self::$appFolder;
    }

    /**
     * @internal
     *
     * @return string
     */
    function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * Use this to check that a function argument is of type <code>AccessType</code>
     *
     * @internal
     */
    static function checkArg($argName, $argValue)
    {
        if (!($argValue instanceof self)) Checker::throwError($argName, $argValue, __CLASS__);
    }

    /**
     * Use this to check that a function argument is either <code>AccessType</code> or of type
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
