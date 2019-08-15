<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 10.08.2019
 * Time: 22:31
 */
namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class User
 * @ORM\Entity()
 */
class User
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
     * @var ArrayCollection|UserInterests[]
     * @ORM\OneToMany(targetEntity="UserInterests", mappedBy="user", cascade={"all"})
     *
     * @Groups("show")
     */
    protected $interests;

    public function __construct()
    {
        $this->interests = new ArrayCollection();
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
     * @return UserInterests[]|ArrayCollection
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * @param UserInterests[]|ArrayCollection $interests
     */
    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    public function addInterests(UserInterests $interests)
    {
        if (!$this->interests->contains($interests)){
            $this->interests->add($interests);
        }
    }

    public function removeInterests(UserInterests $interests)
    {
        if ($this->interests->contains($interests)){
            $this->interests->removeElement($interests);
        }
    }
}