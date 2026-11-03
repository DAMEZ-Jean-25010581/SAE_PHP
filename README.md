#SAE-S3-2026-27 Cyberlab

```mermaid
classDiagram

    class Character {
        <<sealed abstract>>
        -name : String;
        -gender : char;
        -age : int;
        -hungerMetric : int;
        -tirednessMetric : int;
        -isResting : boolean;
        -isNoisy: boolean;
        -christmasMagicMetric : int;
        #makeASound() String;
        #move() void;
        #eat() void;
        #rest() void;
        #workOut() void;
        #increaseChristmasMagic() void;
        #decreaseChristmasMagic() void;
        #performsActivity() void;
    }

    class Humanoid {

    }

    class Elf {

    }

    class PostmanElf {

    }

    class ChristmasFairy {

    }

    class VisitingChild {

    }

    Humanoid <|-- Elf
    Humanoid <|-- PostmanElf
    Humanoid <|-- ChristmasFairy
    Humanoid <|-- VisitingChild

    class Creature {

    }

    Creature ..> Humanoid : devours

    class Reindeer {

    }

    class Werewolf {

    }

    class LivingSnowman {

    }

    class NorthSpirit {

    }

    Creature <|-- Reindeer
    Creature <|-- Werewolf
    Creature <|-- LivingSnowman
    Creature <|-- NorthSpirit

    Character <|-- Humanoid
    Character <|-- Creature

    class Basic
    
    Food {
        <<enumeration>>
        BISCUITS,
        HOT CHOCOLATE,
        MARSHMALLOWS,
        OATMEAL
    }

    class Sweet {
        <<enumeration>>
        CANDY CANE,
        CANDY
    }

    class Food {
        <<sealed interface>>
        #getNutritionalValue() int
    }

    Food <|.. BasicFood
    Food <|.. Sweet
    
    Character --> Food: eats

    class Entertainable {
        <<interface>>
        #entertain() void;
    }

    Entertainable <|.. Elf
    Entertainable <|.. VisitingChild 

    class Worker {
        <<interface>>
        #work() void;
    }

    Worker <|.. Elf
    Worker <|.. PostmanElf
    Worker <|.. ChristmasFairy

    class FantasticBeing {
        <<interface>>
        #behaveFantastically() void;
    }

    FantasticBeing <|.. Elf
    FantasticBeing <|.. ChristmasFairy
    FantasticBeing <|.. Werewolf
    FantasticBeing <|.. LivingSnowman
    FantasticBeing <|.. NorthSpirit

    class ElfState {
        <<interface>>
    }

    class PranksterState {

    }

    class NormalState {

    }

    ElfState <|.. PranksterState
    ElfState <|.. NormalState


    class Prank {
        <<enumeration>>
        MAGIC MILK,
        SOCK_SOUP,
        ROLLED_TOILET_PAPER,
        GOOGLY_EYES,
        ZIP_LINE
    }

    PranksterState --> Prank
    
    class FuneralEncounter {
        -double RESTING_CHANCE$
        -double NOISY_CHANCE$
        -double WEREWOLF_MULTIPLIER$
        -int WITNESS_MAGIC_LOSS$
        -Random random
        +trigger(List~Building~ buildings)
        ~computeDevourChance(Humanoid, Creature) double
    }

    class Building {
        <<sealed abstract>>
        -double area;
        -int capacity;
        -Cleanliness cleanliness;
        -int numberOfCharacters;
        -List~Character~ characters;
        #toString() String;
        #welcome() void;
        #clean() void;
    }

    class Cleanliness {
        <<enumeration>>
        BAD
        CORRECT
        GOOD
    }

    Building --> Cleanliness : cleanliness

    class SantaHouse {

    }

    class ClockTower {

    }

    class Cabin {

    }

    class Enclosure {

    }

    class CandyShop {

    }

    class Workshop {

    }

    class PostOffice {

    }

    class MagicSchool {

    }

    class ToyShop {

    }

    class Playground {

    }

    class Reserve {

    }

    Building <|-- SantaHouse
    Building <|-- ClockTower
    Building <|-- Cabin
    Building <|-- Enclosure
    Building <|-- CandyShop
    Building <|-- Workshop
    Building <|-- PostOffice
    Building <|-- MagicSchool
    Building <|-- ToyShop
    Building <|-- Playground
    Building <|-- Reserve

    class SantaClaus {
        <<singleton>>
        -SantaClaus INSTANCE$
        -SantaClaus()
        +getInstance()$ SantaClaus
        +examine(Building building)
        +requestMaintenance(Building building)
        +transfer(Character character, Building from, Building to)
    }

    class SantaVillage {
        -SantaClaus INSTANCE$
        -maxArea double;
        -maxCapacity int;
        
    }

    class 
}






```
    