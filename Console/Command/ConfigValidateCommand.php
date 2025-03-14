<?php

declare(strict_types=1);

namespace LBajsarowicz\ConfigValidator\Console\Command;

use Magento\Framework\App\DeploymentConfig\Reader as DeploymentConfigReader;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Console\Cli;
use Magento\Framework\Module\ModuleList\Loader as ModuleListLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see \Magento\Setup\Model\Installer::createModulesConfig
 */
class ConfigValidateCommand extends Command
{
    public function __construct(
        private readonly DeploymentConfigReader $deploymentConfigReader,
        private readonly ModuleListLoader $moduleLoader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('setup:config:validate');
        $this->setDescription('Validate `config.php` contents.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allModules = array_keys($this->moduleLoader->load());
        $deploymentConfig = $this->deploymentConfigReader->load();

        $currentModules = $deploymentConfig[ConfigOptionsListConstants::KEY_MODULES] ?? [];

        $result = [];
        foreach ($allModules as $module) {
            $result[$module] = (int)(!isset($currentModules[$module]) || $currentModules[$module]);
        }

        $missingInCodebase = array_diff_assoc($currentModules, $result);
        $missingInConfig = array_diff_assoc($result, $currentModules);

        if ($missingInCodebase || $missingInConfig) {
            $output->writeln('<error>Contents of `config.php` is not up to date</error>');

            if ($missingInCodebase) {
                $output->writeln(
                    "Modules should be removed from `config.php`: " . implode(', ', array_keys($missingInCodebase)),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }

            if ($missingInConfig) {
                $output->writeln(
                    "Modules missing from `config.php`: " . implode(', ', array_keys($missingInConfig)),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }

            return Cli::RETURN_FAILURE;
        }

        $output->writeln('<info>Contents of `config.php` is up-to-date.</info>');

        return Cli::RETURN_SUCCESS;
    }
}
