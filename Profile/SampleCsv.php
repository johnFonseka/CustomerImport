<?php
/**
 * Class SampleCsv
 * @package Jf\CustomerImport\Profile
 *
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 21:54
 */

namespace Jf\CustomerImport\Profile;

use Jf\CustomerImport\Api\ProfileInterface;
use Jf\CustomerImport\Helper\Data;

class SampleCsv implements ProfileInterface
{
    /**
     * @var Data
     */
    private Data $helper;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileHandler;

    /**
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
        $this->fileHandler = $this->helper->getFilehandler();
    }

    /**
     * Implementation of get data for CSV files.
     *
     * This file will read CSV in to an array via M2 framework function, validate first row column names, and
     * then format other lines into common output format.
     *
     * @param string $file
     * @return array|null
     * @throw FileNotFoundException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getData($file)
    {
        if ($this->helper->fileExists($file)) {
            $csv_content = array_map('str_getcsv', file($file)); // TODO:: use Magento functions
            // $csv_content = $this->fileHandler->fileGetCsv($this->fileHandler->fileOpen($file, 'r'));
            $first_row = true;
            $data = [];
            foreach ($csv_content as $row) {
                if ($first_row) { // validate the first column
                    if (!($row[0] == 'fname' && $row[1] == 'lname' && $row[2] == 'emailaddress')) {
                        break;
                    }
                    $first_row = false;
                } else {
                    $data[] = [
                        'fname' => $row[0],
                        'lname' => $row[1],
                        'emailaddress' => $row[2],
                        'pass' => $this->helper->getRandomText()
                    ];
                }
            }
            return $data;
        }
    }
}
