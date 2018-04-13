<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class MultipleOrderedProperties implements \JsonSerializable
{
    /**
     * @var string
     */
    private $charlie;

    /**
     * @var string
     */
    private $bravo;

    /**
     * @var string
     */
    private $alpha;

    /**
     * @var string
     */
    private $delta;


    /**
     * @param string $charlie
     * @param string $bravo
     * @param string $alpha
     */
    public function __construct($charlie, $bravo, $alpha)
    {
        $this->charlie = (string)$charlie;
        $this->bravo = (string)$bravo;
        $this->alpha = (string)$alpha;
    }


    /**
     * @param string $value
     */
    public function setDelta($value)
    {
        $this->delta = $value;
    }


    public function jsonSerialize()
    {
        $values = [
         'charlie' => $this->charlie,
         'bravo' => $this->bravo,
         'alpha' => $this->alpha,
        ];
        if ($this->delta) {
            $values['delta'] = $this->delta;
        }
        return $values;
    }
}
