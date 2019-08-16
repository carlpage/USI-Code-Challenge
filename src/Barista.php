<?php

namespace Coffee;

class Barista extends CoffeeMaker
{
    public function measure(float $brewSize) {
        return $this::COFFEE_PER_UNIT * $brewSize;
    }

    public function grind(int $coffeeID, float $amount) {
        
    }
}