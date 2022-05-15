<?php
/**
 * Class Customer
 * @package Jf\CustomerImport\Model
 *
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-11 20:39
 */

namespace Jf\CustomerImport\Model;

use Jf\CustomerImport\Helper\Data;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;

class Customer extends AbstractModel
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $store;

    /**
     * @var CustomerFactory
     */
    private CustomerFactory $customer;

    /**
     * @var Data $helper
     */
    private Data $helper;

    /**
     * @param StoreManagerInterface $store
     * @param CustomerFactory $customer
     * @param Data $helper
     */
    public function __construct(StoreManagerInterface $store, CustomerFactory $customer, Data $helper)
    {
        $this->store = $store;
        $this->customer = $customer;
        $this->helper = $helper;
    }

    /**
     * This function will accept data array add all customers to Magento
     *
     * @param array $customer_data
     * @return array
     */
    public function add($customer_data)
    {
        $total = $success = 0;
        foreach ($customer_data as $customer) {
            if ($this->addSingle($customer)) {
                $success++;
            }
            $total++;
        }
        return ['success' => $success, 'total' => $total];
    }

    /**
     * Add single customer to Magento
     *
     * @param array $customer
     * @return bool
     */
    private function addSingle($customer)
    {
        try {
            $_customer = $this->customer->create();
            $_customer->setWebsiteId($this->store->getWebsite()->getWebsiteId());
            $_customer->setEmail($customer['emailaddress'])
                ->setFirstname($customer['fname'])
                ->setLastname($customer['lname'])
                ->setPassword($customer['pass']);

            $_customer->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
