<?php

namespace Wenprise\Mvc\Config;

class Constant
{
    /**
     * Save the retrieved dataset.
     *
     * @var array
     */
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Load a list of constant variables.
     *
     * @return \Wenprise\Mvc\Config\Constant
     */
    public function make()
    {
        if (!empty($this->data)) {
            foreach ($this->data as $name => $value) {
                $name = strtoupper($name);
                defined($name) ? $name : define($name, $value);
            }
        }

        return $this;
    }
}
