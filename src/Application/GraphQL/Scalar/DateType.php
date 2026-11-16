<?php

declare(strict_types=1);

namespace Application\GraphQL\Scalar;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;

final class DateType
{
    private const FORMAT = 'Y-m-d';

    /**
     * PHP -> GraphQL (réponse).
     */
    public static function serialize(\DateTimeInterface $value): string
    {
        return $value->format(self::FORMAT);
    }

    /**
     * GraphQL -> PHP, valeur passée via les variables.
     */
    public static function parseValue(mixed $value): \DateTimeImmutable
    {
        if (!\is_string($value)) {
            throw new Error(\sprintf('Date must be a string in "%s" format.', self::FORMAT));
        }

        $date = \DateTimeImmutable::createFromFormat('!'.self::FORMAT, $value);

        if (false === $date || $date->format(self::FORMAT) !== $value) {
            throw new Error(\sprintf('Invalid date "%s", expected format "%s".', $value, self::FORMAT));
        }

        return $date;
    }

    /**
     * GraphQL -> PHP, valeur écrite en dur dans la requête.
     */
    public static function parseLiteral(Node $valueNode): \DateTimeImmutable
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Date must be a string.', [$valueNode]);
        }

        return self::parseValue($valueNode->value);
    }
}
