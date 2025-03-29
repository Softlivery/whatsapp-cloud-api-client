<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class PayloadMapper implements PayloadMapperInterface
{
    /**
     * Maps an associative array to an instance of the specified class, initializing properties with values from the array.
     *
     * @param array $payload The input array containing data to populate the class properties.
     * @param string $targetClass The fully qualified name of the class to map the data to.
     *                          The class must exist, or an exception will be thrown.
     * @return object An instance of the specified class with mapped properties.
     * @throws InvalidArgumentException If the specified class does not exist.
     * @throws ReflectionException If the class is an internal class that cannot be instantiated without invoking the constructor.
     */
    public function map(array $payload, string $targetClass): mixed
    {
        if (!class_exists($targetClass)) {
            throw new InvalidArgumentException("Class $targetClass does not exist.");
        }

        $reflection = new ReflectionClass($targetClass);
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
                        $object->$key = array_map(fn($item) => (new PayloadMapper)->map($item, $nestedClass), $value);
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
                    $object->$key = (new PayloadMapper)->map($value, $type);
                } else {
                    $object->$key = $value;
                }
            }
        }

        return $object;
    }
}
