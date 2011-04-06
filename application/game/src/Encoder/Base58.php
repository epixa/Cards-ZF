<?php
/**
 * Epixa - Cards
 */

namespace Game\Encoder;

/**
 * Tool for encoding and decoding numbers in base58
 *
 * Algorithm is based off of the algorithm used by flic.kr:
 * @link http://www.flickr.com/groups/api/discuss/72157616713786392
 *
 * @category   Module
 * @package    Game
 * @subpackage Encoder
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Base58
{
    /**
     * @var string
     */
    protected $_alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';


    /**
     * Encodes the given integer value in base 58
     * 
     * @param  integer $value
     * @return void
     */
    public function encode($value)
    {
        $length = strlen($this->_alphabet);
        $encoded = '';

        while ($value >= $length) {
            $div = intval($value / $length);
            $mod = $value - ($length * $div);
            $encoded = $this->_alphabet[$mod] . $encoded;
            $value = $div;
        }

        if ($value) {
            $encoded = $this->_alphabet[$value] . $encoded;
        }

        return $encoded;
    }

    /**
     * Decodes the given base 58 integer
     * 
     * @param  string $value
     * @return integer
     */
    public function decode($value)
    {
        $length = strlen($this->_alphabet);
        $decoded = 0;

        $multi = 1;
        while (strlen($value) > 0) {
            $digit = $value[strlen($value) - 1];
            $decoded += $multi * strpos($this->_alphabet, $digit);
            $multi = $multi * $length;
            $value = substr($value, 0, -1);
        }

        return $decoded;
    }
}