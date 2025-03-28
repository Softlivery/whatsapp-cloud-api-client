<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class PayloadMapper
{
    /**
     * Maps an associative array to an instance of the specified class, initializing properties with values from the array.
     *
     * @param array $payload The input array containing data to populate the class properties.
     * @param string $className The fully qualified name of the class to map the data to.
     *                          The class must exist, or an exception will be thrown.
     * @return object An instance of the specified class with mapped properties.
     * @throws InvalidArgumentException If the specified class does not exist.
     * @throws ReflectionException If the class is an internal class that cannot be instantiated without invoking the constructor.
     */
    public static function map(array $payload, string $className): object
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class $className does not exist.");
        }

        $reflection = new ReflectionClass($className);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($payload as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                $type = $property->getType()?->getName();

                if (is_array($value) && $property->getDocComment() &&
                    preg_match('/@var ([^\[]+)/', $property->getDocComment(), $matches)
                ) {
                    $nestedClass = trim($matches[1]);

                    if (!class_exists($nestedClass) && !str_starts_with($nestedClass, '\\')) {
                        $nestedClass = $reflection->getNamespaceName() . '\\' . $nestedClass;
                    }

                    if (class_exists($nestedClass)) {
                        $object->$key = array_map(fn($item) => self::map($item, $nestedClass), $value);
                    } else {
                        $object->$key = $value;
                    }
                } elseif (in_array($type, ['int', 'float', 'string', 'bool'])) {
                    $object->$key = match ($type) {
                        'int' => intval($value),
                        'float' => floatval($value),
                        'string' => (string)$value,
                        'bool' => boolval($value)
                    };
                } elseif ($type && class_exists($type)) {
                    $object->$key = self::map($value, $type);
                } else {
                    $object->$key = $value;
                }
            }
        }

        return $object;
    }
}
