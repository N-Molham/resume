<?php

use Illuminate\Support\Facades\Route;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

// encoding cache
$GLOBALS['encoding_cache'] = [];

Route::get('/', static function () {

    /**
     * Encode passed string to ASCII code
     *
     * @param string $string
     *
     * @return string
     */
    $encodeString = static function (string $string) : string {
        $encoded = '';
        $hash_key = md5($string);
        if (isset($GLOBALS['encoding_cache'][$hash_key])) {
            return $GLOBALS['encoding_cache'][$hash_key];
        }

        for ($i = 0, $len = strlen($string); $i < $len; $i++) {
            $encoded .= "&#".ord($string[$i]).';';
        }

        // cache & return
        $GLOBALS['encoding_cache'][$hash_key] = $encoded;

        return $encoded;
    };

    /**
     * Minify CSS
     */
    $minifyCss = static function (string ...$files) : string {
        $files = array_map(static fn ($file) => resource_path('/css/'.$file), $files);

        return (new CSS(...$files))->minify();
    };

    /**
     * Minify Javascript
     */
    $minifyJs = static function (string ...$files) : string {
        $files = array_map(static fn ($file) => resource_path('/js/'.$file), $files);

        return (new JS(...$files))->minify();
    };

    return view('resume', compact('encodeString', 'minifyJs', 'minifyCss'));
});
