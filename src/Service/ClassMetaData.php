<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class ClassMetaData
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {  
    }

    /**
     * @return mixed[]
     */
    public function getSetterWithData(string $className): array
    {
        $classMetadata = $this->entityManager->getClassMetadata($className);
        $objectFieldMappings = $classMetadata->fieldMappings;

        $setters = [];
        foreach ($objectFieldMappings as $fieldMapping) {
            if ($fieldMapping['fieldName'] == 'id') {
                continue;
            }

            $setters[$fieldMapping['fieldName']] = 'set' . ucfirst($fieldMapping['fieldName']);
        }

        $finalArray = [];
        $settersKeys = array_flip($setters);
        foreach ($settersKeys as $key => $value) {
            foreach ($objectFieldMappings as $fieldMapping) {
                if ($fieldMapping['fieldName'] === $value) {
                    $finalArray[$key] = $fieldMapping;
                }
            }
        }

        return $this->createValuesForSetMethods($finalArray);
    }

    /**
     * @param mixed[] $settersWithMetadata
     * 
     * @return mixed[]
     */
    private function createValuesForSetMethods(array $settersWithMetadata): array
    {
        $finalArray = [];
        
        foreach ($settersWithMetadata as $methodName => $metadata) {
            $finalArray[$methodName] = $this->createValue($metadata);
        }

        return $finalArray;
    }

    /**
     * @param mixed[] $fieldMetaData
     */
    private function createValue(array $fieldMetaData): mixed
    {
        $type = $fieldMetaData['type'];

        if ($fieldMetaData['unique'] === true) {
            return uniqid();
        }

        switch ($type) {
            case 'string':
                return 'test';
            case 'integer':
                return 1;
            case 'datetime':
                return new \DateTime();
            case 'datetime_immutable':
                return new \DateTimeImmutable();
            case 'boolean':
                return true;
            case 'array':
                return [];
            case 'json':
                return [];
            default:
                return null;
        }
    }
}
