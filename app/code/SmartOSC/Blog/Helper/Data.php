<?php


namespace SmartOSC\Blog\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    protected $_storeManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate

    ) {
        $this->_storeManager = $storeManager;
        $this->_fileSystem = $fileSystem;
        $this->_customerSession = $customerSession;
        $this->_localeDate = $localeDate;
        parent::__construct($context);
    }

    public function getImageUrl($image)
    {
        $path = $this->_fileSystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'smartosc/blogs/blog/image/'
        );

        if (file_exists($path . $image)) {
            $path = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            return $path . 'smartosc/blogs/blog/image/' . $image;
        } else {
            return '';
        }
    }

    public function getCustomerId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getCustomerId();
        }
        return null;
    }
    public function convertTime($dateTime,$isReserved = false)
    {
        if ($isReserved) {
            $date = new \DateTime($dateTime, new \DateTimeZone($this->_localeDate->getDefaultTimezone()));
            $date->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone()));
        } else {
            $date = new \DateTime($dateTime, new \DateTimeZone($this->_localeDate->getConfigTimezone()));
            $date->setTimezone(new \DateTimeZone($this->_localeDate->getDefaultTimezone()));
        }

        $dateTime = $date->format('Y-m-d H:i:s');
        return $dateTime;
    }
}