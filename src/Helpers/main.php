<?php


use Plutuss\SauceCore\Config\Config;
use Plutuss\SauceCore\Http\Redirect\Redirect;
use Plutuss\SauceCore\Routing\Router;
use Plutuss\SauceCore\Session\Session;
use Plutuss\SauceCore\Support\Str;

if (!function_exists('env')) {
    /**
     * @param string $name
     * @param $default
     * @return mixed
     */
    function env(string $name, $default = null): mixed
    {
        try {
            return getenv($name);
        } catch (Exception $e) {
            return $default;
        }

    }

}

if (!function_exists('route')) {
    /**
     * @param $name
     * @param $id
     * @return Exception|string
     */
    function route($name, $id = null): Exception|string
    {
        $url = Router::getRouteFromName($name);

        if (!empty($id)) {
            $url = preg_replace('/(^\/)|(\/$)/', '', $url);
            $urlExplode = explode('/', $url);

            if (isset($urlExplode[2]) && $urlExplode[2] == 'edit') {
                return "/$urlExplode[0]/$id/$urlExplode[2]";
            }
            if (isset($urlExplode[2]) && $urlExplode[2] == 'destroy') {
                return "/$urlExplode[0]/$id/$urlExplode[2]";
            }
            return "/$urlExplode[0]/$id";
        }
        return $url;
    }

}

if (!function_exists('redirect')) {
    /**
     * @param string $url
     */
    function redirect(string $url)
    {
        Redirect::getInstance()->to($url);
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param string|object $class
     * @return string
     */
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('getPluralWort')) {
    /**
     * @param string $phrase
     * @param int $value
     * @return string
     */
    function getPluralWort(string $phrase, int $value): string
    {
        $plural = '';
        if ($value > 1) {
            for ($i = 0; $i < strlen($phrase); $i++) {
                if ($i == strlen($phrase) - 1) {
                    $plural .= ($phrase[$i] == 'y') ? 'ies' : (($phrase[$i] == 's' || $phrase[$i] == 'x' || $phrase[$i] == 'z' || $phrase[$i] == 'ch' || $phrase[$i] == 'sh') ? $phrase[$i] . 'es' : $phrase[$i] . 's');
                } else {
                    $plural .= $phrase[$i];
                }
            }
            return $plural;
        }
        return $phrase;
    }
}


if (!function_exists('slug')) {

    /**
     * @param string $text
     * @param string $divider
     * @return string
     */
    function slug(string $text, string $divider = '-'): string
    {
        return Str::slug($text, $divider);
    }
}

if (!function_exists('session')) {

    /**
     * @return Session
     */
    function session(): Session
    {
        return new Session;
    }
}

if (!function_exists('config')) {
    /**
     * @param $path
     * @return mixed
     */
    function config($path): mixed
    {
        return (new Config)->get($path);
    }
}
