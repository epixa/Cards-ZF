<?php
/**
 * Epixa - Cards
 */

namespace User\Service;

use Epixa\Service\AbstractDoctrineService,
    User\Model\Group as GroupModel,
    Epixa\Exception\NotFoundException,
    Doctrine\ORM\NoResultException;

/**
 * @category   Module
 * @package    User
 * @subpackage Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Group extends AbstractDoctrineService
{
    const UNVERIFIED = 'unverified';
    
    /**
     * Gets a group by the given code
     * 
     * @param  string $code
     * @return GroupModel
     * @throws NotFoundException If no group is found with that code
     */
    public function getByCode($code)
    {
        $em = $this->getEntityManager();
        
        $repo = $em->getRepository('User\Model\Group');

        $qb = $repo->createQueryBuilder('ug');
        
        $repo->restrictToCode($qb, $code);
        
        try {
            $group = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundException(
                sprintf('Code `%s` not found', $code), null, $e
            );
        }
        
        return $group;
    }
}