<?php
namespace Platform\Database\Traits;

use Platform\Database\Exceptions\MethodNotFoundException;

trait MagicRepositoryMethods
{
    /**
     * The repository supports magic method calls to getBy* where the * equates to a valid
     * field name on the entity. Eg:
     *
     * $repository->getByFieldName('value') would create a new query and try and find records
     * based on the field 'fieldName'
     *
     * @param string $method
     * @param array $arguments
     * @throws MethodNotFoundException
     * @return resource
     */
    public function __call($method, $arguments)
    {
        // Handles method calls for basic queries like getById or requireById
        foreach (['getBy', 'requireBy'] as $queryType) {
            if (strstr($method, $queryType)) {
                $field = snake_case(str_replace($queryType, '', $method));
                $value = $arguments[0];

                return $this->$queryType($field, $value);
            }
        }

        throw new MethodNotFoundException($this, $method);
    }
}
