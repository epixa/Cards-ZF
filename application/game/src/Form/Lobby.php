<?php
/**
 * Epixa - Cards
 */

namespace Game\Form;

use Epixa\Form\BaseForm;

/**
 * This form contains all of the base fields for games with their default
 * requirements.
 *
 * @category   Module
 * @package    Game
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Lobby extends BaseForm
{
    public function init()
    {
        $this->addElement('text', 'name', array(
            'required' => true,
            'label' => 'Name',
            'validators' => array(
                array('StringLength', true, array(1, 255))
            )
        ));

        $this->addElement('text', 'password', array(
            'required' => true,
            'label' => 'Password',
            'validators' => array(
                array('StringLength', true, array(1, 255))
            )
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Submit'
        ));
    }
}