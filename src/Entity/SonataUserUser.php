<?php

namespace App\Entity;

//use App\Repository\UserRepository;
use Sonata\UserBundle\Entity\BaseUser;
//use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
//@ORM\Entity(repositoryClass=UserRepository::class)
/**
 * @ORM\Entity
 * @ORM\Table(name="`fos_user__user`")
 */
class SonataUserUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
