<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 22/05/2018
 * Time: 15:15
 */

namespace Project\BookingBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Service\ComputePrice;
use Project\BookingBundle\Service\MailSender;
use Project\BookingBundle\Service\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManager
{
    const SESSION_CURRENT_BOOKING = "bookingData";
    const AGE_SENIOR = 60;
    const AGE_TEENAGER = 12;
    const AGE_KIDS = 4;
    const NEED_DATA_BOOKING = 2;
    const NEED_DATA_TICKETS = 3;
    const NEED_ID_ORDER = 4;
    const GROUP_VALID_BOOKING = "booking_group_validation";
    const GROUP_VALID_VISITOR = "visitor_group_validation";
    const GROUP_ID_ORDER = "confirmed_order";


    /**
     * @var SessionInterface
     */
    private $session;

    private $entityManager;
    /**
     * @var Payment
     */
    private $payment;
    /**
     * @var MailSender
     */
    private $mailSender;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * BookingManager constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     * @param Payment $payment
     * @param MailSender $mailSender
     * @param ValidatorInterface $validator
     * @param ComputePrice $computePrice
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, Payment $payment, MailSender $mailSender, ValidatorInterface $validator)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->payment = $payment;
        $this->mailSender = $mailSender;
        $this->validator = $validator;
    }

    /**
     * @return Booking
     */
    public function init()
    {
        $currentSession = $this->session->get(self::SESSION_CURRENT_BOOKING);
        if ($currentSession)
        {
            return $currentSession;
        }

        $booking = new Booking();
        $this->session->set(self::SESSION_CURRENT_BOOKING, $booking);
        return $booking;
    }

    /**
     * @param Booking $booking
     */
    public function computePrice(Booking $booking)
    {
        $priceTotal = 0;
        foreach ($booking->getVisitors() as $visitor) {
            $age = $visitor->getAge();
            if ($age < BookingManager::AGE_KIDS) {
                $price = Booking::PRICE_FREE;
            } else if ($age < BookingManager::AGE_TEENAGER) {
                $price = Booking::PRICE_CHILD;
            } else if ($age < BookingManager::AGE_SENIOR) {
                $price = Booking::PRICE_NORMAL;
            } else {
                $price = Booking::PRICE_OLD;
            }

            if($visitor->getReducePrice()  && $price > Booking::PRICE_REDUCED){
                $price = Booking::PRICE_REDUCED;
            }else{
                $visitor->setReducePrice(false);
            }

            if($booking->getTypeOfTicket() == Booking::TYPE_HALF_DAY){
                $price = $price * Booking::PRICE_HALF_DAY_MULTIPLICATOR;
            }

            $visitor->setPrice($price);
            $priceTotal += $visitor->getPrice();
        }
        $booking->setTotalPrice($priceTotal);
    }

    /**
     *
     *
     * @param Booking $booking
     */
    public function generateTickets(Booking $booking)
    {
        while($booking->getNbTickets() != $booking->getVisitors()->count()){
            if($booking->getNbTickets() > $booking->getVisitors()->count()){
                $booking->addVisitor(new Visitor());
            }
            if($booking->getNbTickets() < $booking->getVisitors()->count()){
                $booking->removeVisitor($booking->getVisitors()->last());
            }
        }
    }

    /**
     * @param array $step
     * @return mixed
     */
    public function getCurrentBooking($step = null)
    {
        $groups = [];

        switch ($step){
            case self::NEED_DATA_BOOKING :
                $groups = [self::GROUP_VALID_BOOKING];
                break;
            case self::NEED_DATA_TICKETS :
                $groups = [self::GROUP_VALID_BOOKING, self::GROUP_VALID_VISITOR];
                break;
            case self::NEED_ID_ORDER :
                $groups = [self::GROUP_VALID_BOOKING, self::GROUP_VALID_VISITOR, self::GROUP_ID_ORDER];
                break;
        }

        $currentSession = $this->session->get(self::SESSION_CURRENT_BOOKING);

        if(!$currentSession){
            throw new NotFoundHttpException('La page demandée n\'existe pas pour le moment car aucune donnée n\'a été envoyé');
        }else{
            $errors = $this->validator->validate($currentSession, null, $groups);

            if (count($errors) > 0)
            {
                throw new NotFoundHttpException('La page demandée n\'existe pas pour le moment car aucune donnée n\'a été envoyé');
            }
        }
        return $currentSession;
    }

    /**
     * @return mixed
     */
    public function removeCurrentBooking()
    {
        return $this->session->remove(self::SESSION_CURRENT_BOOKING);
    }

    /**
     * @param Booking $booking
     * @param Request $request
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function executePayment(Booking $booking,Request $request)
    {
        $transactionId = $this->payment->isPayment($request, $booking);

        if($transactionId !== false){
            $booking->setOrderId($transactionId);
            $this->entityManager->persist($this->getCurrentBooking());
            $this->entityManager->flush();

            $this->mailSender->sendBookingConfirmation($booking);

            return true;
        }

        return false;
    }
}