<?php
namespace Lqdt\Coj\Model\Entity;

use Adbar\Dot;

/**
 * This trait adds useful methods to get and set values in JSON fields
 * @version 1.0.0
 * @since   1.0.0
 * @license MIT
 * @author  Liqueur de Toile <contact@liqueurdetoile.com>
 */
trait JsonTrait
{
    private $_field;
    private $_path;
    private $_data;

    private function _parse(string $datfield) : void
    {
        $parts = explode('@', $datfield);
        $this->_field = (string) $parts[1];
        $this->_path = (string) $parts[0];
        $this->_data = dot($this->get($this->_field));
    }

    public function jsonGet(string $datfield)
    {
        $this->_parse($datfield);
        return $this->_data->get($this->_path);
    }

    public function jsonSet($datfield, $value = null) : self
    {
        if (is_array($datfield)) {
            foreach ($datfield as $field => $value) {
                $this->jsonSet($field, $value);
            }
            return $this;
        }

        $this->_parse($datfield);
        $this->_data->set($this->_path, $value);
        $this->set($this->_field, $this->_data->all());
        return $this;
    }

    public function jsonIsset(string $datfield) : bool
    {
        $this->_parse($datfield);
        return $this->_data->has($this->_path);
    }

    public function jsonUnset($datfield) : self
    {
        if (is_array($datfield)) {
            foreach ($datfield as $field) {
                $this->jsonUnset($field);
            }
            return $this;
        }

        $this->_parse($datfield);
        $this->_data->delete($this->_path);
        $this->set($this->_field, $this->_data->all());
        return $this;
    }
}
