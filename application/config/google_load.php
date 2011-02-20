<?php
/*
|--------------------------------------------------------------------------
| API for error tracking
|--------------------------------------------------------------------------
|
| Get your API at http://code.google.com/apis/ajaxsearch/signup.html
| The API key costs nothing, and allows google to contact you directly if google detect an issue with your site.
|
*/
$config["gl_api"] = "ABQIAAAAZwrnE63okqlKy_y6Q_0VoxQKV7gD2cHVNDd15nnQV4D85THHVhQO4TBkM1U_1Ylb4uo2xW4t-FXbww";

/*
|--------------------------------------------------------------------------
| Javascript libraries versioning
|--------------------------------------------------------------------------
|
| These can be changed to specific versions as long as they are hosted by google ajax libraries
| Using just the beginning number makes a wildcard and is a awesome way to get the newest version
| package as long as the version base number (ie 1 for jquery) is not released then this will need to
| be updated.
|
*/
$config["n_version"] = array(
    "jquery"=>"1",
    "jqueryui"=>"1",
    "prototype"=>"1",
    "scriptaculous"=>"1",
    "mootools"=>"1",
    "dojo"=>"1",
    "swfobject"=>"2",
    "yui"=>"2",
    "ext-core"=>"3",
    "chrome-frame"=>"1",
    "webfont"=>"1"
);
/*
|--------------------------------------------------------------------------
| Development settings
|--------------------------------------------------------------------------
|
| If you want the html output to be cleaner with a benchmark turen dev to TRUE
| To serve the javascript files uncompressed to uncompressed to TRUE
|
*/
$config["dev"] = TRUE;
$config["uncompressed"] = FALSE;
?>