<?php
declare(strict_types=1);

namespace Denal05\Ad0e702ExerciseAutomaticEventObserver\Observer;

use Denal05\Ad0e702ExerciseAutomaticEventObserver\Helper\prado3\framework\Util\TVarDumper;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\OfflinePayments\Model\Cashondelivery;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class ConfigurePaymentIsActive implements ObserverInterface
{
    private $logger;
    protected $checkoutSession;
    protected $messageManager;

    public function __construct(
        PsrLoggerInterface $logger,
        CheckoutSession $checkoutSession,
        MessageManager $messageManager
    ) {
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        $exitCode = 0;

        try {
            $currentPaymentMethodCode = $observer->getEvent()->getMethodInstance()->getCode();
            if ($currentPaymentMethodCode === Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE) {
                $observer->getEvent()->getResult()->setData('is_available', false);
            }
            $this->logger->debug(__METHOD__ . ': ' . TVarDumper::dump($currentPaymentMethodCode));
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $exitCode = 1;
        }

        return $exitCode;
    }
}
