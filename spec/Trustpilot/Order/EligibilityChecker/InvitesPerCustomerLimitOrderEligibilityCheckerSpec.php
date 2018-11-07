<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Doctrine\Common\Collections\ArrayCollection;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\InvitesPerCustomerLimitOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;

class InvitesPerCustomerLimitOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(InvitesPerCustomerLimitOrderEligibilityChecker::class);
    }

    public function let(): void
    {
        $this->beConstructedWith(0);
    }

    public function it_returns_true_when_there_is_no_limit(OrderTrustpilotAwareInterface $order): void
    {
        $order->getCustomer()->shouldNotBeCalled();

        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_true_when_limit_is_not_reached(OrderTrustpilotAwareInterface $order, CustomerTrustpilotAwareInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $order->getTrustpilotEmailsSent()->willReturn(2);
        $customer->getOrders()->willReturn(new ArrayCollection([$order->getWrappedObject()]));

        $this->beConstructedWith(3);
        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_false_when_limit_is_reached(OrderTrustpilotAwareInterface $order, CustomerTrustpilotAwareInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $order->getTrustpilotEmailsSent()->willReturn(3);
        $customer->getOrders()->willReturn(new ArrayCollection([$order->getWrappedObject()]));

        $this->beConstructedWith(3);
        $this->isEligible($order)->shouldReturn(false);
    }

    public function it_returns_false_when_limit_is_reached_on_other_clients_orders(OrderTrustpilotAwareInterface $order, OrderTrustpilotAwareInterface $otherOrder, CustomerTrustpilotAwareInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $order->getTrustpilotEmailsSent()->willReturn(0);

        $otherOrder->getTrustpilotEmailsSent()->willReturn(3);
        $customer->getOrders()->willReturn(new ArrayCollection([$order->getWrappedObject(), $otherOrder->getWrappedObject()]));

        $this->beConstructedWith(3);
        $this->isEligible($order)->shouldReturn(false);
    }
}
