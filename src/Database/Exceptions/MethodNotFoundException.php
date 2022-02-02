<?php
namespace Platform\Database\Exceptions;

/**
 * Class MethodNotFoundException
 *
 * Custom exception for handling method calls via magic methods (such as __call).
 */
class MethodNotFoundException extends \Exception
{
    public function __construct($class, $method)
    {
        $this->message = 'Method ['.$method.'] does not exist on class ['.get_class($class).']';
    }
}
