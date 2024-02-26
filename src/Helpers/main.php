<?php


use Plutuss\CodeMaster\Config\Config;
use Plutuss\CodeMaster\Http\Redirect\Redirect;
use Plutuss\CodeMaster\Routing\Router;
use Plutuss\CodeMaster\Session\Session;
use Plutuss\CodeMaster\Support\Str;

if (!function_exists('envt')) {
    /**
     * @param string $name
     * @param $default
     * @return mixed
     */
    function envt(string $name, $default = null): mixed
    {
        return $_ENV[$name] ?? $default;
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

if (!function_exists('root_dir')) {

    function root_dir()
    {
        return APP_DIR;
    }
}
if (!function_exists('app_path')) {

    function app_path()
    {
        return root_dir() . '/../app';
    }
}

if (!function_exists('app_path_from_console')) {

    function app_path_from_console()
    {
        return $_SERVER['PWD'] . '/app';
    }
}