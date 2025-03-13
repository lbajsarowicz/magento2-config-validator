<?php

declare(strict_types=1);

namespace LBajsarowicz\ConfigValidator\Console;

use LBajsarowicz\ConfigValidator\Console\Command\ConfigValidateCommand;
use Magento\Framework\Console\CommandListInterface;
use Magento\Framework\ObjectManagerInterface;

class CommandList implements CommandListInterface
{
    public function __construct(private readonly ObjectManagerInterface $objectManager)
    {
    }

    public function getCommands()
    {
        return [
            $this->objectManager->get(ConfigValidateCommand::class)
        ];
    }
}
