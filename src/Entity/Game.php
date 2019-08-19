<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 19.08.2019
 * Time: 19:45
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Game
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Game
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
     * @var League|null
     * @ORM\ManyToOne(targetEntity="League")
     */
    protected $league;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("show")
     */
    protected $date;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $round;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $status;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("show")
     */
    protected $homeTeamId;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $homeTeamName;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("show")
     */
    protected $homeTeamGoals;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("show")
     */
    protected $awayTeamId;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups("show")
     */
    protected $awayTeamName;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("show")
     */
    protected $awayTeamGoals;

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
        return $this->league ? $this->league->getId() : null;
    }

    /**
     * @return int|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int|null $date
     */
    public function setDate(?int $date)
    {
        $this->date = $date;
    }

    /**
     * @return null|string
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param null|string $round
     */
    public function setRound(?string $round)
    {
        $this->round = $round;
    }

    /**
     * @return null|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null|string $status
     */
    public function setStatus(?string $status)
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getHomeTeamId()
    {
        return $this->homeTeamId;
    }

    /**
     * @param int|null $homeTeamId
     */
    public function setHomeTeamId(?int $homeTeamId)
    {
        $this->homeTeamId = $homeTeamId;
    }

    /**
     * @return null|string
     */
    public function getHomeTeamName()
    {
        return $this->homeTeamName;
    }

    /**
     * @param null|string $homeTeamName
     */
    public function setHomeTeamName(?string $homeTeamName)
    {
        $this->homeTeamName = $homeTeamName;
    }

    /**
     * @return int|null
     */
    public function getHomeTeamGoals()
    {
        return $this->homeTeamGoals;
    }

    /**
     * @param int|null $homeTeamGoals
     */
    public function setHomeTeamGoals(?int $homeTeamGoals)
    {
        $this->homeTeamGoals = $homeTeamGoals;
    }

    /**
     * @return int|null
     */
    public function getAwayTeamId()
    {
        return $this->awayTeamId;
    }

    /**
     * @param int|null $awayTeamId
     */
    public function setAwayTeamId(?int $awayTeamId)
    {
        $this->awayTeamId = $awayTeamId;
    }

    /**
     * @return null|string
     */
    public function getAwayTeamName()
    {
        return $this->awayTeamName;
    }

    /**
     * @param null|string $awayTeamName
     */
    public function setAwayTeamName(?string $awayTeamName)
    {
        $this->awayTeamName = $awayTeamName;
    }

    /**
     * @return int|null
     */
    public function getAwayTeamGoals()
    {
        return $this->awayTeamGoals;
    }

    /**
     * @param int|null $awayTeamGoals
     */
    public function setAwayTeamGoals(?int $awayTeamGoals)
    {
        $this->awayTeamGoals = $awayTeamGoals;
    }
}