<?php

namespace MarJose123\Ninshiki;

use Illuminate\Http\Request;
use Illuminate\Support\Stringable;
use MarJose123\Ninshiki\Concerns\InteractsWithEvents;

class Ninshiki
{
    use InteractsWithEvents;

    public static array $jsonVariables = [];

    /**
     * Get the app name utilized by Nova.
     */
    public static function name(): Stringable|string
    {
        return config('ninshiki.name') ?? 'Ninshiki';
    }

    /**
     * Get the JSON variables that should be provided to the global Nova JavaScript object.
     *
     * @return array<string, mixed>
     */
    public static function jsonVariables(Request $request): array
    {
        return collect(static::$jsonVariables)->map(function ($variable) use ($request) {
            return is_object($variable) && is_callable($variable)
                ? $variable($request)
                : $variable;
        })->all();
    }

    /**
     * Provide additional variables to the global Nova JavaScript object.
     *
     * @param  array<string, mixed>  $variables
     */
    public static function config(array $variables): static
    {
        if (empty(static::$jsonVariables)) {
            static::$jsonVariables = [
                'debug' => fn () => config('app.debug') || app()->environment('testing'),
                'userId' => request()->session()->get('userId') ?? null,
            ];
        }

        static::$jsonVariables = array_merge(static::$jsonVariables, $variables);

        return new static;
    }

    /**
     * Get the current Ninshiki version.
     */
    public static function version(): string
    {
        return once(function () {
            return \Composer\InstalledVersions::getPrettyVersion('ninshiki-project/ninshiki');
        });
    }
}
