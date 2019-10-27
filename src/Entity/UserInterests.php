<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 10.08.2019
 * Time: 22:54
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class UserInterests
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class UserInterests
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups("show")
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="interests")
     */
    protected $user;

    /**
     * @var Team|null
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="interests")
     */
    protected $team;

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
     * @Groups("show")
     */
    public function getUserId()
    {
        return $this->getUser() ? $this->getUser()->getId() : null;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Team|null
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     */
    public function setTeam(?Team $team)
    {
        $this->team = $team;
    }

    /**
     * @Groups("show")
     */
    public function getTeamName()
    {
        return $this->getTeamId() ? $this->getTeam()->getName() : null;
    }

    /**
     * @Groups("show")
     */
    public function getTeamId()
    {
        return $this->getTeam() ? $this->getTeam()->getId() : null;
    }
}