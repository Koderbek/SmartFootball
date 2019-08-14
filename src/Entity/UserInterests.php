<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 10.08.2019
 * Time: 22:54
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

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
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="interests")
     */
    protected $user;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $team;

    /**
     * @var League|null
     * @ORM\ManyToOne(targetEntity="League")
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
     * @return int
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param int $team
     */
    public function setTeam(int $team)
    {
        $this->team = $team;
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

}