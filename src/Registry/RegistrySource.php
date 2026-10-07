<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

enum RegistrySource: string
{
    case Remote = 'remote';
    case Local = 'local';
    case Fallback = 'fallback';
}
