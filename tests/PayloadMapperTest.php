<?php

namespace Softlivery\WhatsappCloudApiClient\Tests;

use InvalidArgumentException;
use Softlivery\WhatsappCloudApiClient\PayloadMapper;
use PHPUnit\Framework\TestCase;

class PayloadMapperTest extends TestCase
{
    /**
     * Test that the map method correctly maps array values to a class object.
     */
    public function testMapWithValidPayload()
    {
        // Setup
        $payload = ['name' => 'John Doe', 'age' => 30, 'isActive' => true];
        $className = DummyClass::class;

        // Act
        $result = PayloadMapper::map($payload, $className);

        // Assert
        $this->assertInstanceOf($className, $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals(30, $result->age);
        $this->assertTrue($result->isActive);
    }

    /**
     * Test mapping with nested objects.
     */
    public function testMapWithNestedObjects()
    {
        // Setup
        $payload = [
            'name' => 'Parent',
            'child' => ['name' => 'Child', 'age' => 10, 'height' => 3.7]
        ];
        $className = ParentClass::class;

        // Act
        $result = PayloadMapper::map($payload, $className);

        // Assert
        $this->assertInstanceOf($className, $result);
        $this->assertEquals('Parent', $result->name);
        $this->assertInstanceOf(ChildClass::class, $result->child);
        $this->assertEquals('Child', $result->child->name);
        $this->assertEquals(10, $result->child->age);
    }

    /**
     * Test mapping with a class having array of objects.
     */
    public function testMapWithArrayOfObjects()
    {
        // Setup
        $payload = [
            'items' => [
                ['value' => 'Item 1'],
                ['value' => 'Item 2']
            ]
        ];
        $className = CollectionClass::class;

        // Act
        $result = PayloadMapper::map($payload, $className);

        // Assert
        $this->assertInstanceOf($className, $result);
        $this->assertCount(2, $result->items);
        $this->assertInstanceOf(ItemClass::class, $result->items[0]);
        $this->assertEquals('Item 1', $result->items[0]->value);
        $this->assertEquals('Item 2', $result->items[1]->value);
    }

    /**
     * Test exception when mapping to a non-existing class.
     */
    public function testMapWithNonExistentClass()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class NonExistentClass does not exist.');

        // Act
        PayloadMapper::map([], 'NonExistentClass');
    }

    /**
     * Test exception when mapping a class with properties that cannot be instantiated without a constructor.
     */
    public function testMapWithUninstantiableClass()
    {
        //In PHP 5.6.0 onwards, this exception is limited only to internal classes that are final.
        //$this->expectException(ReflectionException::class);

        // Act
        $result = PayloadMapper::map([], UninstantiableClass::class);
        $this->assertInstanceOf(UninstantiableClass::class, $result);
    }
}

// Dummy test classes used in the tests
class DummyClass
{
    public string $name;
    public int $age;
    public bool $isActive;
}

class ChildClass
{
    public string $name;
    public int $age;
    public float $height;
}

class ParentClass
{
    public string $name;
    public ChildClass $child;
}

class ItemClass
{
    public string $value;
}

class CollectionClass
{
    /**
     * @var ItemClass[]
     */
    public array $items;
}

class UninstantiableClass
{
    public function __construct(string $requiredParameter)
    {
    }
}
