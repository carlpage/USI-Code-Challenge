<?php

namespace Coffee;

/**
 * Let's brew some digital coffee!!!
 */
abstract class CoffeeMaker
{
    /**
     * The SqlLiteDB LessQL PDO instance
     *
     * @var \PDO
     */
    protected $db;

    /**
     * How much coffee weight is used per brewed coffee unit
     * e.g. how many ounces (weight) of coffee brews 1 fluid ounce
     * (Units aren't really important)
     */
    const COFFEE_PER_UNIT = 0.08333333;

    public function __construct($db)
    {
        var_dump($db);
        $this->db = $db;
    }

    /**
     * Gets the amount of coffee required to brew a cup of the given size
     *
     * @param float $cupSize size of the cup
     *
     * @return float
     */
    // step one. do i simply need to uncomment this?
    // public function getAmountOfCoffee(float $cupSize = 12): float
    // {
    //     return $this::COFFEE_PER_UNIT * $cupSize;
    // }

    /**
     * Needs to return how much coffee (weight) to use
     *
     * @param float $brewSize how much to brew for
     *
     * @return float
     */
    abstract public function measure(float $brewSize): float;

    /**
     * Needs to grind the coffee, which reduces the amount of coffee,
     * and adds to the amount of ground coffee.
     *
     * @param integer $coffeeID
     * @param float $amount
     *
     * @return integer The ID of the ground coffee, or -1 if not possible
     */
    abstract public function grind(int $coffeeID, float $amount): int;

    /**
     * Needs to brew the coffee, which consumes the ground coffee for that brew size
     * and needs to fit in the first cup it will all fit in (and fills it that amount).
     *
     * @param integer $groundCoffeeID
     * @param float $brewSize
     *
     * @return boolean true if everything brewed ok, or false if it didn't work
     */
    abstract public function brew(int $groundCoffeeID, float $brewSize): bool;

    /**
     * Needs to sip the coffee, which consumes a bit of coffee.
     *
     * @param integer $cupID
     * @param float $sipSize
     *
     * @return string that annoying "ahhh" sound, or sadness when the coffee is gone :-(
     */
    abstract public function sip(int $cupID, float $sipSize = 0.2): string;
}