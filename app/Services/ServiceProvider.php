<?php
/**
 * Custom Service Provider
 * ========================================
 *
 * Automatically routes method calls to appropriate service class
 *
 */
namespace App\Services;

use ReflectionClass;

class ServiceProvider {
	/**
	 * Call Static
	 *
	 * @param String $method
	 * @param Array $parameters
     * @return \App\Service\*
	 */
    public static function __callStatic(string $method, array $parameters) {
        // construct class namespace base on method name
        $class = preg_replace('/[A-Z]/', '\\\\$0', $method);
		$class = 'App\Services\\' . ucwords($class);

        // create a reflector class base on constructed class namespace
        // and pass the parameters/arguments
		$reflector = new ReflectionClass($class);
		return $reflector->newInstanceArgs($parameters);
    }

    public static function arrayToKeyVal(array $arr, string $key, string $val) {
        $data = [];
        foreach ($arr as $v) {
            if (!isset($v[$key])) {
                continue;
            }
            
            $data[$v[$key]] = self::parseTPLVal($v, $val);
        }

        return $data;
    }

    private static function parseTPLVal($arr, $tpl) {
        $cols = explode('|', $tpl);

        $val = '';
        foreach ($cols as $col) {
            if ($col[0] === '[' && $col[strlen($col)-1] == ']') {
                $val .= substr($col, 1, -1);
                continue;
            }
            
            $val .= $arr[$col];
        }

        return $val;
    }
}
