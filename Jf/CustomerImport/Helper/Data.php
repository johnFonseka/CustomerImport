<?php
/**
 * Class Data
 *
 * @package Jf\CustomerImport\Helper
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 22:43
 */

namespace Jf\CustomerImport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\Dir;
use Magento\Framework\Filesystem\Driver\File;

class Data extends AbstractHelper
{
    /**
     * @param Context $context
     * @param Dir $dir
     * @param File $fileDrive
     */
    public function __construct(Context $context, Dir $dir, File $fileDrive)
    {
        $this->dir = $dir;
        $this->fileDrive = $fileDrive;
        parent::__construct($context);
    }

    /**
     * String operation to convert 'sample-csc' to SampleCsv
     *
     * @param string $profile_arg
     * @return string
     */
    public function getClassNameFromProfileArg($profile_arg)
    {
        $name_parts = array_map('ucfirst', explode("-", $profile_arg));
        return implode('', $name_parts);
    }

    /**
     * Get the actual class implementation with proper class name.
     *
     * @param string $profile
     * @return mixed|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getProfileClass($profile)
    {
        $properClassName = $this->getClassNameFromProfileArg($profile);
        $module_path = $this->dir->getDir($this->_getModuleName());

        $class_path = $module_path.'/Profile/'.$properClassName.'.php';
        if ($this->fileDrive->isExists($class_path)) {
            $properClassName = 'Jf\CustomerImport\Profile\\'.$properClassName;
            return new $properClassName($this);
        }
    }

    /**
     * Return a random text for customer password.
     *
     * @param int $length
     * @return false|string
     */
    public function getRandomText(int $length = 10)
    {
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $length = strlen($comb) < $length ? strlen($comb):$length;
        $shuffle = str_shuffle($comb);
        return substr($shuffle, 0, $length);
    }
}
