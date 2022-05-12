<?php
/**
 *
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 21:57
 */

namespace Jf\CustomerImport\Profile;

interface ProfileInterface
{
    /**
     * This function will return data when file is given.
     * Output data must be in this format.
     * $data = [
     *      [
     *          'fname' => 'john',
     *          'lname' => 'fonseka',
     *          'emailaddress' => 'someone@example.com'
     *      ],
     *      ...
     * ];
     *
     * @param string $file
     * @return array|null
     */
    public function getData($file);
}
