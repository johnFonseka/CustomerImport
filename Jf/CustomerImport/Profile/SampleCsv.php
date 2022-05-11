<?php
/**
 * Class SampleCsv
 * @package Jf\CustomerImport\Profile
 *
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 21:54
 */

namespace Jf\CustomerImport\Profile;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Jf\CustomerImport\Profile\ProfileInterface;
use Jf\CustomerImport\Helper\Data;

class SampleCsv implements ProfileInterface
{
    private Data $helper;

    /**
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Implementation of get data for CSV files.
     *
     * Purposely not checking file existence as it is already done in Console/CustomerImport
     *
     * @param string $file
     * @return array
     * @throw FileNotFoundException
     */
    public function getData($file)
    {
        $csv_content = array_map('str_getcsv', file($file)); // TODO:: use Magento functions
        $first_row = true;
        $data = [];
        foreach ($csv_content as $row) {
            if ($first_row) { // validate the first column
                if (! ($row[0] == 'fname' && $row[1] == 'lname' && $row[2] == 'emailaddress')) {
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
