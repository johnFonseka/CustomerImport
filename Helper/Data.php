<?php
/**
 * Class Data
 *
 * Contains various utility functions
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
     * @var File $fileDrive
     */
    private File $fileDrive;

    /**
     * @var Dir $dir
     */
    private Dir $dir;

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
     * Get the actual class implementation by command name.
     *
     * Ideally we can extend this functionality to a 'Profile Handler' class. But for this solution, this is fine.
     * This file is non-testable. As Magento 2 module is not setting up when we run just Data file on phpUnit:68
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
     * Magento ruleset strictly prohibiting the use of md5() even for string generation(?). So I have to write this down.
     *
     * @param int $length
     * @return false|string
     */
    public function getRandomText(int $length = 10)
    {
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $length = strlen($charset) < $length ? strlen($charset):$length;
        $shuffle = str_shuffle($charset);
        return substr($shuffle, 0, $length);
    }

    /**
     * Wrapper for file drive file exists.
     *
     * Native file manipulations are discouraged. Therefore, changed it to Magento framework function.
     *
     * @param $file_path
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function fileExists($file_path)
    {
        return $this->fileDrive->isExists($file_path);
    }

    /**
     * Return Magento 2 File Handler
     *
     * Returning file handler in case if profile extension developers need to use various file functions.
     *
     * @return File
     */
    public function getFilehandler()
    {
        return $this->fileDrive;
    }
}
