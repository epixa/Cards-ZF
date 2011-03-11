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
class Group extends EntityRepository
{
    /**
     * Restricts the given query to the result that matches the given code
     *
     * @param QueryBuilder $qb
     * @param string       $code
     */
    public function restrictToCode(QueryBuilder $qb, $code)
    {
        $qb->andWhere('ug.code = :code')
           ->setParameter('code', $code);
    }
}