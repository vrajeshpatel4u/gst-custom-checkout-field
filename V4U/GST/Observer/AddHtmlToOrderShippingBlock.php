<?php
namespace V4U\GST\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\TemplateFactory;
use Magento\Store\Model\ScopeInterface;

class AddHtmlToOrderShippingBlock implements ObserverInterface
{
    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * AddHtmlToOrderShippingBlock constructor.
     *
     * @param TemplateFactory $templateFactory
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TemplateFactory $templateFactory
    ) {
        $this->templateFactory = $templateFactory;
    }

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        if($observer->getElementName() == 'sales.order.info') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();

            /** @var \Magento\Framework\View\Element\Template $gstNumberBlock */
            $gstNumberBlock = $this->templateFactory->create();
            $gstNumberBlock->setGstNumber($order->getGstNumber());
            $gstNumberBlock->setTemplate('V4U_GST::order_info_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $gstNumberBlock->toHtml();
            $observer->getTransport()->setOutput($html);
        }

        return $this;
    }
}