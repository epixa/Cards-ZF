<?php
/**
 * Epixa - Cards
 */

namespace Game\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Game\Encoder\Base58 as Base58Encoder;

/**
 * @category   Module
 * @package    Game
 * @subpackage Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Lobby extends EntityRepository
{
    /**
     * Restricts the given query to the result that matches the given id
     *
     * @param QueryBuilder $qb
     * @param integer      $id
     */
    public function restrictToId(QueryBuilder $qb, $id)
    {
        $qb->andWhere('gl.id = :id')
           ->setParameter('id', $id);
    }

    /**
     * Restricts the given query to the result that matches the given key
     *
     * @param QueryBuilder $qb
     * @param string       $key
     */
    public function restrictToKey(QueryBuilder $qb, $key)
    {
        $encoder = new Base58Encoder();
        $id = $encoder->decode($key);

        $this->restrictToId($qb, $id);
    }
}