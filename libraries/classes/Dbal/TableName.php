<?php

declare(strict_types=1);

namespace PhpMyAdmin\Dbal;

use Stringable;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-immutable
 */
final class TableName implements Stringable
{
    /**
     * @see https://dev.mysql.com/doc/refman/en/identifier-length.html
     * @see https://mariadb.com/kb/en/identifier-names/#maximum-length
     */
    private const MAX_LENGTH = 64;

    /**
     * @var string
     * @psalm-var non-empty-string
     */
    private $name;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $name)
    {
        Assert::stringNotEmpty($name);
        Assert::maxLength($name, self::MAX_LENGTH);
        Assert::notEndsWith($name, ' ');
        $this->name = $name;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $name): self
    {
        return new self($name);
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
