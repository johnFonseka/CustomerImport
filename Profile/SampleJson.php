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

class SampleJson implements ProfileInterface
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
     * This function will read the file content to a string, parse it as JSON, convert it to an array. Then it will
     * construct it to the common output format and returns it.
     *
     * @param string $file
     * @return array|null
     * @throw FileNotFoundException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getData($file)
    {
        if ($this->helper->fileExists($file)) {
            // $file_content = $string = file_get_contents($file); // Again need to replace "file_get_contents()"
            $file_content = $this->fileHandler->fileGetContents($file);
            $json_data = json_decode($file_content, true);
            $data = [];
            if (!is_null($json_data)) {
                foreach ($json_data as $row) {
                    if (isset($row['fname']) && isset($row['lname']) && isset($row['emailaddress'])) {
                        $data[] = [
                            'fname' => $row['fname'],
                            'lname' => $row['lname'],
                            'emailaddress' => $row['emailaddress'],
                            'pass' => $this->helper->getRandomText()
                        ];
                    }
                }
            }
            return $data;
        }
    }
}
