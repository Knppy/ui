<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use RuntimeException;

class Installer
{
    public function __construct(protected string $targetPath, protected bool $force, protected Registry $registry) {}

    /**
     * @throws FileNotFoundException
     */
    public function install(array $component): array
    {
        $installedFiles = [];

        foreach ($component['files'] as $file) {
            $destination = $this->targetPath.'/'.basename($file);

            if (File::exists($destination) && ! $this->force) {
                throw new RuntimeException("File already exists: $destination. Use --force to replace.");
            }

            $directory = dirname($destination);

            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $source = $this->registry->srcPath().'/'.$file;

            $content = File::get($source);
            File::put($destination, $content);

            $installedFiles[] = $destination;
        }

        return $installedFiles;
    }
}
