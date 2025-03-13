<?php

declare(strict_types=1);

use LBajsarowicz\ConfigValidator\Console\CommandList;
use Magento\Framework\Console\CommandLocator;

if (PHP_SAPI === 'cli') {
    CommandLocator::register(CommandList::class);
}
