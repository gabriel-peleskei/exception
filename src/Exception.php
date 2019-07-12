<?php
/**
 * Created by PhpStorm.
 * User: Gabriel Peleskei <gabriel.peleskei@gmail.com>
 * @license MIT
 */

namespace GabrielPeleskei;

use Throwable;

/**
 * Class Exception
 *
 * @package GabrielPeleskei
 */
class Exception extends \Exception implements \JsonSerializable
{
    protected $_uniqueID;
    protected $_method;
    protected $_data;
    protected $_date;

    /**
     * Exception constructor.
     *
     * {@inheritdoc}
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     *
     * @version 0.1.0
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        try {
            $this->_date = new \DateTime();
        } catch (\Exception $e) {
        }
    }

    /**
     * Static instance generator
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     *
     * @return $this
     * @see \Exception::__construct()
     * @version  0.1.0
     */
    public static function _($message = '', $code = 0, Throwable $previous = null)
    {
        return new static($message, $code, $previous);
    }

    /**
     * Throws the current Exception:<br>
     * <br/>
     * if (!#action) throw new Exception(...) <br/>
     * // with this method: <br/>
     * #action or Exception::_(...)->throwIt() <br/>
     * #assignment or Exception::_(...)->throwIt() <br/>
     *
     * @throws $this
     * @version 0.1.0
     */
    public function throwIt()
    {
        throw $this;
    }


    /**
     * @param mixed $uniqueID
     *
     * @return $this|static
     * @version 0.1.0
     */
    public function uniqueID($uniqueID)
    {
        $this->_uniqueID = $uniqueID;

        return $this;
    }

    /**
     * Date of creation
     * @return \DateTime|null
     * @version 0.1.0
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param string $method
     *
     * @return $this
     * @version 0.1.0
     */
    public function method($method = __METHOD__)
    {
        $this->_method = (string)$method;

        return $this;
    }

    /**
     * @return mixed|null
     * @version 0.1.0
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return mixed
     * @version 0.1.0
     */
    public function getUniqueID()
    {
        return $this->_uniqueID;
    }

    /**
     * Set more data for better error logging and handling
     *
     * @param $data
     *
     * @return $this|static
     * @version 0.1.0
     */
    public function data($data)
    {
        $this->_data = $data;

        return $this;
    }

    /**
     * @return mixed
     * @version 0.1.0
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return \Exception|string
     * @version 0.1.0
     */
    protected function _getPreviousJson()
    {
        $previous = $this->getPrevious();
        if ($previous) {
            if ( ! ($previous instanceof \JsonSerializable) && is_object($previous)) {
                $previous = get_class($previous);
            }
        }

        return $previous;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @version 0.1.0
     */
    public function jsonSerialize()
    {
        return [
            'date'               => $this->_date ? $this->_date->format('c') : date('c'),
            'method'             => $this->getMethod(),
            'message'            => $this->getMessage(),
            'code'               => $this->getCode(),
            'unique_id'          => $this->getUniqueID(),
            'file'               => $this->getFile(),
            'line'               => $this->getLine(),
            'data'               => $this->getData(),
            'trace'              => $this->getTrace(),
            'previous_exception' => $this->_getPreviousJson(),
        ];
    }

    /**
     * @return string
     * @uses Exception::jsonSerialize()
     * @ises json_encode()
     * @version 0.1.0
     */
    public function __toString()
    {
        return print_r($this->jsonSerialize(), true);
    }
}