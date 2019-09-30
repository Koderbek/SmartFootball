<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 20.08.2019
 * Time: 21:11
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Team
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Team
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
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $name;

    /**
     * @var League|null
     * @ORM\ManyToOne(targetEntity="League", inversedBy="teams")
     */
    protected $league;

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
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name)
    {
        $this->name = $name;
    }

    /**
     * @return League|null
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param League|null $league
     */
    public function setLeague(?League $league)
    {
        $this->league = $league;
    }

    /**
     * @Groups("show")
     */
    public function getLeagueId()
    {
        return $this->getLeague() ? $this->getLeague()->getId() : null;
    }
}