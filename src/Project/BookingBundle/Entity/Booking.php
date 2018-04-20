<?php

namespace Project\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Project\BookingBundle\Repository\BookingRepository")
 */
class Booking
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
     * @var int
     *
     * @ORM\Column(name="normal", type="integer")
     */
    private $normal;

    /**
     * @var int
     *
     * @ORM\Column(name="child", type="integer")
     */
    private $child;

    /**
     * @var int
     *
     * @ORM\Column(name="old", type="integer")
     */
    private $old;

    /**
     * @var int
     *
     * @ORM\Column(name="reduce", type="integer")
     */
    private $reduce;


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
     * Set normal
     *
     * @param integer $normal
     *
     * @return Booking
     */
    public function setNormal($normal)
    {
        $this->normal = $normal;

        return $this;
    }

    /**
     * Get normal
     *
     * @return int
     */
    public function getNormal()
    {
        return $this->normal;
    }

    /**
     * Set child
     *
     * @param integer $child
     *
     * @return Booking
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return int
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set old
     *
     * @param integer $old
     *
     * @return Booking
     */
    public function setOld($old)
    {
        $this->old = $old;

        return $this;
    }

    /**
     * Get old
     *
     * @return int
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * Set reduce
     *
     * @param integer $reduce
     *
     * @return Booking
     */
    public function setReduce($reduce)
    {
        $this->reduce = $reduce;

        return $this;
    }

    /**
     * Get reduce
     *
     * @return int
     */
    public function getReduce()
    {
        return $this->reduce;
    }
}

