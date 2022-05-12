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

class SampleJson implements ProfileInterface
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
        $file_content = $string = file_get_contents($file); // Again need to to replace "file_get_contents()"
        $json_data = json_decode($file_content, true);
        $data = [];
        if (! is_null($json_data)) {
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
