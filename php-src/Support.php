<?php

namespace kalanis\kw_modules;


use kalanis\kw_paths;


/**
 * Class Support
 * @package kalanis\kw_modules
 * Basic class with supporting methods
 */
class Support
{
    /**
     * @param string $param
     * @return array<string|int, string|int|float|bool|array<string|int>>
     */
    public static function paramsIntoArray(string $param): array
    {
        parse_str($param, $result);
        return $result;
    }

    /**
     * @param array<string|int, string|int|float|bool|array<string|int>> $param
     * @return string
     */
    public static function paramsIntoString(array $param): string
    {
        return http_build_query($param);
    }

    public static function normalizeNamespacedName(string $moduleName): string
    {
        return implode('\\', // MUST be backslashes!! - translate to class name
            array_map('ucfirst',
                array_map(['\kalanis\kw_modules\Support', 'normalizeModuleName'],
                    array_filter(
                        array_filter(
                            kw_paths\Stuff::linkToArray($moduleName)
                        ), ['\kalanis\kw_paths\Stuff', 'notDots']
                    )
                )
            )
        );
    }

    /**
     * @param string $name
     * @return string[]
     */
    public static function moduleNameFromTemplate(string $name): array
    {
        return array_map(
            'ucfirst',
            array_map(
                ['\kalanis\kw_modules\Support', 'normalizeModuleName'],
                array_map(
                    'strtolower',
                    explode('--', $name)
                )
            )
        );
    }

    public static function clearModuleName(string $name): string
    {
        return strtr($name, ['/' => '', '!' => '']);
    }

    public static function normalizeModuleName(string $moduleName): string
    {
        return implode('', array_map(['\kalanis\kw_modules\Support', 'moduleNamePart'], explode('-', $moduleName)));
    }

    public static function moduleNamePart(string $moduleName): string
    {
        return ucfirst(strtolower($moduleName));
    }

    public static function templateModuleName(string $moduleName): string
    {
        if (false != preg_match_all('#([A-Z][a-z0-9]*)#u', $moduleName, $matches)) {
            return implode('_', array_map('mb_strtoupper', $matches[1]));
        } else {
            return mb_strtoupper($moduleName);
        }
    }

    public static function linkModuleName(string $moduleName): string
    {
        if (false != preg_match_all('#([A-Z][a-z0-9]*)#u', $moduleName, $matches)) {
            return implode('-', array_map('mb_strtolower', $matches[1]));
        } else {
            return mb_strtolower($moduleName);
        }
    }
}
