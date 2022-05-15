<?php
/**
 * Class CustomerImport
 *
 * This class is the gateway class for the command line. It will take inputs from CLI, execute and return the output.
 *
 * @package Jf\CustomerImport\Console
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 15:18
 */

namespace Jf\CustomerImport\Console;

use Jf\CustomerImport\Model\Customer;
use Jf\CustomerImport\Api\ProfileInterface;
use Jf\CustomerImport\Helper\Data;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerImport extends Command
{
    private const PROFILE = 'profile';
    private const FILE = 'file';

    /**
     * This variable is holding model for customer related functions. Like adding customers etc.
     *
     * @var Customer
     */
    private Customer $customer;

    /**
     * Helper class to covert strings, get classes etc
     *
     * @var Data
     */
    private Data $helper;

    /**
     * @param Customer $customer
     * @param Data $helper
     */
    public function __construct(Customer $customer, Data $helper)
    {
        $this->customer = $customer;
        $this->helper = $helper;
        parent::__construct();
    }

    /**
     * Overriding configure method to include arguments and command name
     *
     * @author John Fonseka <shan4max@gmail.com>
     * @datetime 2022-05-12 00:26
     */
    protected function configure()
    {
        $arguments = [
            new InputOption(self::PROFILE, '-p', InputOption::VALUE_REQUIRED, 'Profile'),
            new InputOption(self::FILE, '-f', InputOption::VALUE_REQUIRED, 'File'),
        ];
        $this->setName('customer:import')
            ->setDescription('Import customer data by files.')
            ->setDefinition($arguments);
        parent::configure();
    }

    /**
     * Caller function for CLI command line call
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln("Execution started");

            $profile = $input->getOption(self::PROFILE);
            $file = $input->getOption(self::FILE);

            if ($profile && $file) {
                $profileClass = $this->getProfile($profile);
                if ($profileClass) {
                    $data = $profileClass->getData($file);
                    if ($data) {
                        $results = $this->customer->add($data);
                        $output->writeln("Import completed. Success: <info>" . $results['success'] .
                            "</info>, Total: <fg=red>" . $results['total'] . "</>");
                    } else {
                        $output->writeln("<error>Invalid file or file path. </error>");
                    }
                } else {
                    $output->writeln("<error>Profile does not exists. </error>");
                }
            } else {
                $output->writeln("<error>Argument Error. </error>");
            }
        } catch (\Exception $exception) {
            $output->writeln("<error>Error: ".$exception->getMessage()."</error>");
        }
    }

    /**
     * Get actual class implementation for profile from the command line argument
     *
     * @param string $profile_name
     * @return false|ProfileInterface
     */
    private function getProfile($profile_name)
    {
        $class = $this->helper->getProfileClass($profile_name);
        if ($class instanceof ProfileInterface) {
            return $class;
        }
        return false;
    }
}
