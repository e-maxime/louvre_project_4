<?php

namespace Project\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visitor
 *
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\VisitorRepository")
 */
class Visitor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(groups={"visitor_group_validation"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank(groups={"visitor_group_validation"})
     */
    private $firstName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducePrice", type="boolean")
     */
    private $reducePrice;

    /**
     * @var Booking
     * @ORM\ManyToOne(targetEntity="Project\BookingBundle\Entity\Booking", inversedBy="visitors", cascade={"persist"})
     */
    private $booking;


    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Visitor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Visitor
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Visitor
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Visitor
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set reducePrice
     *
     * @param boolean $reducePrice
     *
     * @return Visitor
     */
    public function setReducePrice($reducePrice)
    {
        $this->reducePrice = $reducePrice;

        return $this;
    }

    /**
     * Get reducePrice
     *
     * @return bool
     */
    public function getReducePrice()
    {
        return $this->reducePrice;
    }

    /**
     * Set ticket
     *
     * @param \Project\BookingBundle\Entity\Booking $booking
     *
     * @return Visitor
     */
    public function setBooking(Booking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Project\BookingBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


    public function getAge(){
        return $this->booking->getDayToVisit()->diff($this->birthday)->y;
    }

}
