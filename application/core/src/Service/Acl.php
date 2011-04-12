<?php
/**
 * Epixa - Cards
 */

namespace Core\Service;

use Epixa\Service\AbstractDoctrineService,
    Epixa\Acl\AclService,
    Core\Model\AclRule;

/**
 * @category   Module
 * @package    Core
 * @subpackage Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Acl extends AbstractDoctrineService implements AclService
{
    /**
     * Load the specific resource information and related rules into the acl
     *
     * @param  \Zend_Acl $acl
     * @param  string    $resource
     */
    public function loadResourceRules(\Zend_Acl $acl, $resource)
    {
        if (!$acl->has($resource)) {
            $acl->addResource($resource);
        }
        
        $em   = $this->getEntityManager();
        $repo = $em->getRepository('Core\Model\AclRule');

        $qb = $repo->createQueryBuilder('car');

        $repo->restrictToResource($qb, $resource);

        foreach ($qb->getQuery()->getResult() as $rule) {
            $acl->allow(
                $rule->roleId,
                $rule->resourceId,
                $rule->privilege,
                $rule->assertion
            );
        }
    }
}