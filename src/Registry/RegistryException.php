<?php

declare(strict_types=1);

namespace Ui\Ui\Registry;

use RuntimeException;
use Throwable;

class RegistryException extends RuntimeException
{
    /**
     * The reference is not a valid component name or @registry/name.
     */
    public static function invalidReference(string $reference): self
    {
        return new self("Invalid component reference [{$reference}]. Use [name] or [@registry/name].");
    }

    /**
     * The referenced registry namespace is not configured.
     */
    public static function unknownRegistry(string $namespace): self
    {
        return new self("Registry [{$namespace}] is not configured.");
    }

    /**
     * The registry could not be reached or the item does not exist.
     */
    public static function unavailable(string $location, ?Throwable $previous = null): self
    {
        return new self("Unable to fetch registry item from [{$location}].", 0, $previous);
    }

    /**
     * The registry returned something that is not a valid item.
     */
    public static function invalidItem(string $location, string $reason): self
    {
        return new self("Registry item at [{$location}] is invalid: {$reason}.");
    }

    /**
     * A registry file would be written outside of its alias directory.
     */
    public static function unsafePath(string $path): self
    {
        return new self("Registry file path [{$path}] is not allowed.");
    }

    /**
     * components.json is missing or unreadable.
     */
    public static function missingComponentsJson(): self
    {
        return new self('components.json was not found or is invalid. Run ui:install first.');
    }

    /**
     * components.json does not define the alias needed for a file type.
     */
    public static function missingAlias(string $alias): self
    {
        return new self("components.json does not define the [aliases.{$alias}] path.");
    }
}
