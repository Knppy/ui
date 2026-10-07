<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

use Illuminate\Support\Facades\File;

class Installer
{
    /**
     * @param  array<mixed>  $componentsJson  The decoded components.json.
     */
    public function __construct(
        private readonly Registry $registry,
        private readonly array $componentsJson,
    ) {}

    /**
     * Create an installer from the project's components.json.
     *
     * @throws RegistryException
     */
    public static function fromProject(Registry $registry): self
    {
        $path = base_path('components.json');

        $json = File::isFile($path) ? json_decode(File::get($path), true) : null;

        if (! is_array($json)) {
            throw RegistryException::missingComponentsJson();
        }

        $registries = is_array($json['registries'] ?? null) ? array_filter($json['registries'], is_string(...)) : [];

        return new self($registry->withRegistries($registries), $json);
    }

    /**
     * Install an item and its registry dependencies into the project.
     *
     * @throws RegistryException
     */
    public function install(string $reference, bool $overwrite = false): InstallResult
    {
        $result = new InstallResult;

        foreach ($this->registry->resolveWithDependencies($reference) as $item) {
            $result = $result->merge($this->installItem($item, $overwrite));
        }

        return $result;
    }

    /**
     * Write the files of a single item.
     *
     * @throws RegistryException
     */
    private function installItem(RegistryItem $item, bool $overwrite): InstallResult
    {
        $written = [];
        $skipped = [];

        foreach ($item->files as $file) {
            $relative = trim($this->aliasFor($file->type), '/').'/'.$file->path;
            $absolute = base_path($relative);

            if (File::exists($absolute) && ! $overwrite) {
                $skipped[] = $relative;

                continue;
            }

            File::ensureDirectoryExists(dirname($absolute));
            File::put($absolute, $file->content);

            $written[] = $relative;
        }

        return new InstallResult(
            $written,
            $skipped,
            $item->composerDependencies,
            $item->npmDependencies,
            $item->source === RegistrySource::Fallback ? [$item->name] : [],
        );
    }

    /**
     * Get the project-relative directory for a file type.
     *
     * @throws RegistryException
     */
    private function aliasFor(string $type): string
    {
        $alias = match ($type) {
            'registry:ui' => 'ui',
            'registry:js' => 'js',
            default => 'components',
        };

        $aliases = is_array($this->componentsJson['aliases'] ?? null) ? $this->componentsJson['aliases'] : [];
        $path = $aliases[$alias] ?? null;

        if (! is_string($path) || trim($path, '/') === '') {
            throw RegistryException::missingAlias($alias);
        }

        return $path;
    }
}
