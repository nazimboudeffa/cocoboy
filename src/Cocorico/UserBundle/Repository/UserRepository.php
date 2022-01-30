<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\UserBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Get active user
     *
     * @param integer $idUser
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getActiveUser($idUser)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where('u.id = :idUser')
            ->setParameter('idUser', $idUser)
            ->andWhere('u.enabled = :enabled')
            ->setParameter('enabled', 1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $email
     *
     * @return mixed|null
     */
    public function findOneByEmail($email)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->addSelect("u")
            ->where('u.email = :email')
            ->setParameter('email', $email);
        try {
            $query = $queryBuilder->getQuery();

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * Get  user by id
     *
     * @param integer $idUser
     *
     * @return mixed
     */
    public function getFindOneQueryBuilder($idUser)
    {
        $queryBuilder =
            $this->createQueryBuilder('u')
                ->addSelect('ut')
                ->leftJoin('u.translations', 'ut')
                ->where('u.id = :idUser')
                ->setParameter('idUser', $idUser);

        return $queryBuilder;
    }

    /**
     * @return array|null
     */
    public function findAllEnabled()
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.enabled = :enabled')
            ->andWhere('u.roles NOT LIKE :roles')
            ->setParameter('enabled', true)
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%');

        try {
            $query = $queryBuilder->getQuery();

            return $query->getResult(AbstractQuery::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            return null;
        }
    }
}
