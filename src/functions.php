<?php

declare(strict_types=1);

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use UnknowSk\Core\Exceptions\Handler;

if (! function_exists('create_app')) {
    function create_app(string $dir): Application
    {
        /*
        |--------------------------------------------------------------------------
        | Create The Application
        |--------------------------------------------------------------------------
        |
        | The first thing we will do is create a new Laravel application instance
        | which serves as the "glue" for all the components of Laravel, and is
        | the IoC container for the system binding all the various parts.
        |
        */

        $app = new Application(
            $dir = ($_ENV['APP_BASE_PATH'] ?? $dir)
        );

        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        defined('DOMAIN') || define('DOMAIN', $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? null);

        if (DOMAIN != null && file_exists($dir.'env'.DS.DOMAIN.DS.'.env')) {
            $app->useEnvironmentPath($dir.DS.'env'.DS.DOMAIN);
        } else {
            $app->useEnvironmentPath($dir);
        }

        if (! file_exists($app->configPath('core.php'))) {
            $app->useConfigPath(dirname(__DIR__).DS.'config');
        }

        $core_config = require $app->configPath('core.php');

        /*
        |--------------------------------------------------------------------------
        | Bind Important Interfaces
        |--------------------------------------------------------------------------
        |
        | Next, we need to bind some important interfaces into the container so
        | we will be able to resolve them when needed. The kernels serve the
        | incoming requests to this application from both the web and CLI.
        |
        */

        $app->singleton(
            Kernel::class,
            $core_config['http']['kernel'] ?? \UnknowSk\Core\Http\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            $core_config['console']['kernel'] ?? \UnknowSk\Core\Console\Kernel::class
        );

        $app->singleton(
            ExceptionHandler::class,
            $core_config['exception']['handler'] ?? Handler::class
        );

        return $app;
    }
}

if (! function_exists('bootstrap_app')) {
    function bootstrap_app(string $dir, bool $is_cli = true): ?int
    {
        $app = create_app($dir);

        /*
        |--------------------------------------------------------------------------
        | Return The Application
        |--------------------------------------------------------------------------
        |
        | This script returns the application instance. The instance is given to
        | the calling script, so we can separate the building of the instances
        | from the actual running of the application and sending responses.
        |
        */

        if ($is_cli) {

            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

            $status = $kernel->handle(
                $input = new Symfony\Component\Console\Input\ArgvInput,
                new Symfony\Component\Console\Output\ConsoleOutput
            );

            /*
            |--------------------------------------------------------------------------
            | Shutdown The Application
            |--------------------------------------------------------------------------
            |
            | Once Artisan has finished running, we will fire off the shutdown events
            | so that any final work may be done by the application before we shut
            | down the process. This is the last thing to happen to the request.
            |
            */

            $kernel->terminate($input, $status);

            return $status;

        }

        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

        $response = $kernel->handle(
            $request = Illuminate\Http\Request::capture()
        )->send();

        $kernel->terminate($request, $response);

        return null;
    }
}

if (! function_exists('not_zero')) {
    function not_zero($var): bool
    {
        return $var !== null && $var !== false && $var !== '';
    }
}

if (! function_exists('array_fill_value_keys')) {
    function array_fill_value_keys(array|object|null $array, int|string|null $key = null): array
    {
        if ($array === null) {
            return [];
        }

        $return = [];
        foreach ($array as $value) {
            if ($key !== null) {
                if (isset($value->$key)) {
                    $return[$value->$key] = $value->$key;
                } elseif (array_key_exists($key, $value) && $value[$key] !== null) {
                    $return[$value[$key]] = $value[$key];
                }
            } else {
                $return[$value] = $value;
            }
        }

        return $return;
    }
}

if (! function_exists('format')) {
    function format(string $formatter, mixed ...$items): mixed
    {
        $first = current($items);
        if (is_array($first) && count($items) === 1) {
            $items = $first;
        }

        if (class_exists('\\UnknowSk\\Core\\Formatters\\'.Str::studly($formatter).'Formatter')) {
            $formatter = '\\UnknowSk\\Core\\Formatters\\'.Str::studly($formatter).'Formatter';

            return call_user_func([new $formatter(...$items), 'format']);
        } elseif (class_exists($formatter)) {
            $class_object = new $formatter();

            if (method_exists($class_object, 'format')) {
                return call_user_func([new $formatter(...$items), 'format']);
            }
        }

        return $first;
    }
}

if (! function_exists('array_merge_unique')) {
    function array_merge_unique($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (! empty($args) && is_array($args)) {
            $argsX = array_shift($args);
            if (! is_countable($argsX)) {
                break;
            }
            foreach ($argsX as $k => $v) {
                if (is_int($k)) {
                    if (in_array($v, $res, true)) {
                        continue;
                    }
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = array_merge_unique($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
}

if (! function_exists('localized_url')) {
    function localized_url($path, $locale = null)
    {
        if (! $locale) {
            $locale = app()->getLocale();
            if ($locale == config('languages.default')) {
                $locale = '';
            }
        }

        return url(($locale ? $locale.'/' : '').$path);
    }
}
