<?php
namespace craft\gql\arguments\elements;

use GraphQL\Type\Definition\Type;

/**
 * Class Asset
 */
class Asset extends BaseElement
{
    /**
     * @inheritdoc
     */
    public static function getArguments(): array
    {
        return array_merge(parent::getArguments(), [
            'volumeId' => Type::listOf(Type::int()),
            'folderId' => Type::listOf(Type::int()),
            'filename' => Type::listOf(Type::string()),
            'kind' => Type::listOf(Type::string()),
            'height' => Type::string(),
            'width' => Type::string(),
            'size' => Type::string(),
            'dateModified' => Type::string(),
            'includeSubfolders' => Type::boolean(),
        ]);
    }
}
