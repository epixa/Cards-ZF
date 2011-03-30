<?php
/**
 * Epixa - Cards
 */

namespace User\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder;

/**
 * @category   Module
 * @package    User
 * @subpackage Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class User extends EntityRepository
{
    /**
     * Restricts the given query to the result that matches the given id
     *
     * @param QueryBuilder $qb
     * @param integer      $id
     */
    public function restrictToId(QueryBuilder $qb, $id)
    {
        $qb->andWhere('u.id = :id')
           ->setParameter('id', $id);
    }

    /**
     * Restricts the given query to the result that matches the given email
     *
     * @param QueryBuilder $qb
     * @param string       $email
     */
    public function restrictToEmail(QueryBuilder $qb, $email)
    {
        $qb->innerJoin('u.profile', 'up')
           ->addSelect('up')
           ->andWhere('up.email = :email')
           ->setParameter('email', $email);
    }
}