<?php

namespace Walish\Paygent\Controller\Paygent;

class Response extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $params = $this->getRequest()->getParams();

        //Walish
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/info.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($params);
    }
}
