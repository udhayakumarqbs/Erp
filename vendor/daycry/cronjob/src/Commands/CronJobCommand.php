<?php

namespace Daycry\CronJob\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Base functionality for enable/disable.
 */
abstract class CronJobCommand extends BaseCommand
{
    /**
     * Command grouping.
     *
     * @var string
     */
    protected $group = 'Cronjob';

    /**
     * Get Config File
     */
    protected function getConfig()
    {
        helper('setting');
    }

    /**
     * Saves the settings.
     */
    protected function saveSettings($status)
    {
        $this->getConfig();

        if (!file_exists(setting('CronJob.filePath') . setting('CronJob.fileName'))) {
            // dir doesn't exist, make it
            if (!is_dir(setting('CronJob.filePath'))) {
                mkdir(setting('CronJob.filePath'));
            }

            $settings = [
                "status" => $status,
                "time" => (new \DateTime())->format('Y-m-d H:i:s')
            ];

            // write the file with json content
            file_put_contents(
                setting('CronJob.filePath') . setting('CronJob.fileName'),
                json_encode(
                    $settings,
                    JSON_PRETTY_PRINT
                )
            );

            return $settings;
        }

        return false;
    }

    /**
     * Gets the settings, if they have never been
     * saved, save them.
     */
    protected function getSettings()
    {
        $this->getConfig();

        if (file_exists(setting('CronJob.filePath') . setting('CronJob.fileName'))) {
            $data = json_decode(file_get_contents(setting('CronJob.filePath') . setting('CronJob.fileName')));
            return $data;
        }

        return false;
    }

    protected function disabled()
    {
        CLI::newLine(1);
        CLI::write('**** Cronjob is now disabled. ****', 'white', 'red');
        CLI::newLine(1);
    }

    protected function enabled()
    {
        CLI::newLine(1);
        CLI::write('**** CronJob is now Enabled. ****', 'black', 'green');
        CLI::newLine(1);
    }

    protected function alreadyEnabled()
    {
        CLI::newLine(1);
        CLI::error('**** CronJob is already Enabled. ****');
        CLI::newLine(1);
    }

    protected function tryToEnable()
    {
        CLI::newLine(1);
        CLI::write('**** WARNING: Task running is currently disabled. ****', 'red');
        CLI::newLine(1);
        CLI::write('**** To re-enable tasks run: cronjob:enable ****', 'black', 'green');
        CLI::newLine(1);
    }
}
