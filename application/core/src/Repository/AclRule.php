<?php
/**
 * Epixa - Cards
 */

namespace Core\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder;

/**
 * @category   Module
 * @package    Core
 * @subpackage Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class AclRule extends EntityRepository
{
    /**
     * Restricts the given query to the result that matches the given id
     *
     * @param QueryBuilder $qb
     * @param string       $resource
     */
    public function restrictToResource(QueryBuilder $qb, $resource)
    {
        $qb->andWhere('car.id = :id')
           ->setParameter('id', $resource);
    }
}