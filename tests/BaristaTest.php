<?php

use PHPUnit\Framework\TestCase;

class CoffeeMakerTest extends TestCase
{
    protected $coffeeMaker;

    private $db;

    public function setUp(): void
    {
        $this->db = new \PDO('sqlite::memory:');
        $this->db->exec(file_get_contents('db_seed.sql'));

        $this->coffeeMaker = new Coffee\Barista($this->db);
    }

    /**
     * @test
     */
    public function it_measures_coffee()
    {
        $this->assertEquals(0.99999996, $this->coffeeMaker->measure(12));
        $this->assertEquals(4.74999981, $this->coffeeMaker->measure(57));
        $this->assertEquals(16.08333269, $this->coffeeMaker->measure(193));
        $this->assertEquals(4193.6864989192, $this->coffeeMaker->measure(50324.24));
    }

    /**
     * @test
     */
    public function it_grinds_the_beans()
    {
        $coffeeResult = $this->db->query('SELECT * FROM coffees');
        $coffees = $coffeeResult->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($coffees as $coffee) {
            $amount = mt_rand(0, floor($coffee['amount_left']));
            $groundID = $this->coffeeMaker->grind($coffee['id'], $amount);

            // assert ground coffee is $amount more
            $groundCoffeeStatement = $this->db->prepare('SELECT * FROM ground_coffee WHERE id=? LIMIT 1');
            $groundCoffeeStatement->execute([$groundID]);
            $groundCoffee = $groundCoffeeStatement->fetch(\PDO::FETCH_ASSOC);
            $this->assertFalse(empty($groundCoffee));
            $this->assertEquals($amount, $groundCoffee['amount']);
            
            // assert whole coffee is $amount less
            $wholeCoffeeStatement = $this->db->prepare('SELECT * FROM coffees WHERE id=? LIMIT 1');
            $wholeCoffeeStatement->execute([$coffee['id']]);
            $wholeCoffee = $wholeCoffeeStatement->fetch(\PDO::FETCH_ASSOC);
            $this->assertFalse(empty($wholeCoffee));
            $this->assertEquals($coffee['amount_left'] - $amount, $wholeCoffee['amount_left']);
        }
        
    }

    /**
     * @test
     */
    public function it_cannot_grind_if_theres_not_enough()
    {
        $coffeeResult = $this->db->query('SELECT * FROM coffees');
        $coffees = $coffeeResult->fetchAll(\PDO::FETCH_ASSOC);
        
        $coffee = $coffees[mt_rand(0, count($coffees)-1)];

        $this->assertEquals(-1, $this->coffeeMaker->grind($coffee['id'], $coffee['amount_left'] + 1.0));
    }

    /**
     * @test
     */
    public function it_brews_the_coffee()
    {
        // insert some ground coffee
        $this->db->prepare('INSERT INTO 
            ground_coffee (from_coffee_id, amount)
            VALUES (1092, 10)'
        )->execute();
        $groundCoffeeID = $this->db->lastInsertID();

        // brew the coffee
        $brewSize = 8.0;

        // check that the ground coffee is no more
        $this->assertTrue($this->coffeeMaker->brew($groundCoffeeID, $brewSize), 'It didn\'t use any coffee grounds');
    }

    /**
     * @test
     */
    public function it_doesnt_brew_too_much_coffee()
    {
        // insert some ground coffee
        $this->db->prepare('INSERT INTO ground_coffee (from_coffee_id, amount) VALUES (1092, 100.1)')->execute();
        $groundCoffeeID = $this->db->lastInsertID();

        // try to brew too much
        $brewSize = 1000.0;

        // watch the world burn
        $this->assertFalse($this->coffeeMaker->brew($groundCoffeeID, $brewSize), 'It brewed too much coffee');
    }

    /**
     * @test
     */
    public function it_sips_the_coffee()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $result = $this->coffeeMaker->sip($cupID);
        $this->assertIsString($result);

        // check that the coffee is now less in the cup
        $statement = $this->db->prepare('SELECT * FROM cups WHERE id=? LIMIT 1');
        $statement->execute([$cupID]);
        $cupCheck = $statement->fetch(\PDO::FETCH_ASSOC);

        $this->assertTrue($cupCheck['coffee_level'] < 8, 'It didn\'t sip any coffee!');    
    }

    /**
     * @test
     */
    public function it_does_not_chug_the_coffee()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $result = $this->coffeeMaker->sip($cupID);
        $this->assertIsString($result);

        // check that the coffee is now less in the cup
        $statement = $this->db->prepare('SELECT * FROM cups WHERE id=? LIMIT 1');
        $statement->execute([$cupID]);
        $cupCheck = $statement->fetch(\PDO::FETCH_ASSOC);

        $this->assertTrue($cupCheck['coffee_level'] < 8.0, 'It didn\'t sip!');
        $this->assertTrue($cupCheck['coffee_level'] >= 5.0, 'Whoa there, that\'s a lot of coffee!');
    }

    /**
     * @test
     */
    public function it_makes_the_super_annoying_ahhh_sound()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $result = $this->coffeeMaker->sip($cupID);
        $this->assertIsString($result);
        $this->assertStringStartsWith('ahh', $result);
    }

    /**
     * @test
     */
    public function it_does_not_make_an_only_slightly_annoying_ah_sound()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $result = $this->coffeeMaker->sip($cupID);
        // $this->assertIsString($result);
        $this->assertFalse('ah' === $result);
    }

    /**
     * @test
     */
    public function it_does_not_make_an_aggressively_annoying_ah_sound()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $result = $this->coffeeMaker->sip($cupID);
        $this->assertIsString($result);
        $this->assertFalse(ctype_upper($result));
    }

    /**
     * @test
     */
    public function it_gets_sad_when_theres_no_coffee_left_to_sip()
    {
        // start with a cup with coffee
        $this->db->prepare('INSERT INTO cups (name, size, coffee_level) VALUES (\'Test Cup\', 8, 8)')->execute();
        $cupID = $this->db->lastInsertID();

        // sip the coffee
        $this->coffeeMaker->sip($cupID, 7.9);
        $result = $this->coffeeMaker->sip($cupID);
        $this->assertIsString($result);
        $this->assertEquals(":-(", $result);
    }
}