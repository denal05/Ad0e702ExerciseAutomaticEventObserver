<?php
declare(strict_types=1);

namespace Denal05\Ad0e702ExerciseAutomaticEventObserver\Observer;

use Denal05\Ad0e702ExerciseAutomaticEventObserver\Helper\prado3\framework\Util\TVarDumper;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\OfflinePayments\Model\Cashondelivery;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class ConfigurePaymentIsActive implements \Magento\Framework\Event\ObserverInterface
{
    private $logger;

    public function __construct(
        PsrLoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $exitCode = 0;

        try {
            $payment = $observer->getData('method_instance');
            if ($payment->getCode === Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE) {
                $observer->getData('result')->setData('is_available', true);
            }
            $this->logger->debug(__METHOD__ . ': ' . TVarDumper::dump($payment));
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $exitCode = 1;
        }

        return $exitCode;
    }
}
