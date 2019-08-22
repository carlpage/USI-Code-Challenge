<?php

namespace Coffee;

class Barista extends CoffeeMaker
{

    public function measure( $brewSize ): float
    {
        return $this::COFFEE_PER_UNIT * $brewSize;
    }

    public function grind( $coffeeID, $amount ): int
    {
        //convert to number 
        $sql = "SELECT * FROM coffees WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$coffeeID]);
        $desired_row = $stmt->fetch();
        $amount_left = $desired_row['amount_left'];
        
        if($amount_left >= $amount) {
            $adjusted_amount = $amount_left - $amount;
            // update ground_coffee table
            $ground_coffee_update_query = 'INSERT INTO ground_coffee (from_coffee_id, amount) VALUES (?, ?)';
            $stmt_2 = $this->db->prepare($ground_coffee_update_query);
            $stmt_2->execute([$coffeeID, $amount]);

            // update coffees table
            $coffee_update_query = 'UPDATE coffees SET amount_left=? WHERE id=?';
            $stmt_3 = $this->db->prepare($coffee_update_query);
            $stmt_3->execute([$adjusted_amount, $coffeeID]);

            return $coffeeID;
        } 

        return -1;
    }

    public function brew( $groundCoffeeID, $brewSize ): bool
    {
        $sql = "SELECT * FROM ground_coffee WHERE id = ?";
        // why is chaining not working?
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$groundCoffeeID]);
        $desired_row = $stmt->fetch();

        if($desired_row['amount'] >= $brewSize) {
            return true;
        }

        return false;
    }

    public function sip( $cupID, $sipSize = 0.2 ): string
    {
        $sql = "SELECT * FROM cups WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cupID]);
        $desired_row = $stmt->fetch();

        if($desired_row['coffee_level'] >= $sipSize) {
            // update cups table
            // take a sip
            $adjusted_amount = $desired_row['coffee_level'] - $sipSize;
            $coffee_update_query = 'UPDATE cups SET coffee_level=? WHERE id=?';
            $stmt_2 = $this->db->prepare($coffee_update_query);
            $stmt_2->execute([$adjusted_amount, $cupID]);
            return 'ahhh';
        }
        
        return ':-(';
    }

}