# USI Dev Code Challenge

Because every great line of code starts with a cup of coffee, your task is to develop the `Barista` class (which extends the `CoffeeMaker` abstract class) to measure coffee, grind the beans, brew the coffee (any method of your choosing), sip the coffee, and make the super annoying "ahhh" sound that people do.

There's a starter `Barista.example.php` to get you going right away.

Oh, and you'll need to keep track of the coffee you're using, since we don't want to run out. :-)

<!-- ## Measuring coffee
The barista needs to know how much coffee grounds are needed for the given brew size. There is a constant `COFFEE_PER_UNIT` that will be helpful. -->

## Grinding coffee
The barista needs to grind the coffee, which reduces the amount of coffee in the cupboard by that amount. If that full amount can't be ground from that particular coffee, none of it should be ground.

## Brewing the coffee
The barista needs to pick a cup to brew the ground coffee into. The cup needs to be able to fit the given brew size. If the cup would overflow, no coffee should be brewed into it.

## Sipping the coffee
We need to sip the coffee to make sure it's tasty. Be sure to sip and not chug ;-)

## Making the "ahhh" sound
We all know it's annoying, but some of us do it anyway. Make that super annoying "ahhhh" sound after sipping the coffee. Don't make it before you sip it--just after.

## Notes:

### API Only
We're only looking for the API here, so no UI is necessary. To see if your class does everything we were expecting, run `composer test` to run the PHPUnit tests for the above tasks.

### DB Setup
Let's just keep things simple and use SQLite3 and the [PHP PDO implementation](https://php.net/manual/en/book.pdo.php). The abstract class will load it up for you and you can make plain sql queries using the sqlite3 object. It's not rocket science, it's just coffee, so although good design practices would want us to have a swappable DB implementation, this is just for funsies.

### Why?
We're hoping to learn a few things about you from this project:
 1. Are you familiar with OOP, abstract classes, and unit testing?
 2. What things did you "figure out" during this process (i.e. are you learning? Trying new things? etc)
 3. Can you stick to the task (scope creepers?)
 4. Will you actually play around and do something silly and imaginative like this?