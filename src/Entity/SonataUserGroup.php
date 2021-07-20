<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Sonata\UserBundle\Entity\BaseGroup;
//use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
//@ORM\Entity(repositoryClass=UserRepository::class)
/**
 * @ORM\Entity
 * @ORM\Table(name="`fos_user__group`")
 */
class SonataUserGroup extends BaseGroup
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
