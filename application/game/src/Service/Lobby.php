<?php
/**
 * Epixa - Cards
 */

namespace Game\Service;

use Epixa\Service\AbstractDoctrineService,
    Game\Model\Lobby as LobbyModel,
    Zend_Auth as Auth,
    Epixa\Exception\DeniedException;

/**
 * @category   Module
 * @package    Game
 * @subpackage Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Lobby extends AbstractDoctrineService
{
    /**
     * Gets a specific lobby given a lobby key
     * 
     * @throws NotFoundException
     * @param  string $key
     * @return Game\Model\Lobby
     */
    public function getByKey($key)
    {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('Game\Model\Lobby');

        $qb = $repo->createQueryBuilder('gl');

        $repo->restrictToKey($qb, $key);

        try {
            $lobby = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundException(
                sprintf('Lobby `%s` not found', $key), null, $e
            );
        }

        return $lobby;
    }

    /**
     * Adds a new lobby with the provided data
     *
     * @param  array $data
     * @return LobbyModel
     */
    public function add(array $data)
    {
        if (!Auth::getInstance()->hasIdentity()) {
            throw new DeniedException('Only logged in users can create new lobbies');
        }

        $data['created_by'] = Auth::getInstance()->getIdentity();

        $lobby = new LobbyModel();
        $lobby->populate($data);

        $em = $this->getEntityManager();
        $em->persist($lobby);
        $em->flush();

        return $lobby;
    }
}