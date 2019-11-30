<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 14.08.2019
 * Time: 21:42
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class League
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class League
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @Groups("show")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * @Groups("show")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * @Groups("show")
     */
    protected $country;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $logo;

    /**
     * @var ArrayCollection|Team[]
     * @ORM\OneToMany(targetEntity="Team", mappedBy="league")
     */
    protected $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return null|string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param null|string $logo
     */
    public function setLogo(?string $logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return Team[]|ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param Team[]|ArrayCollection $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }
}