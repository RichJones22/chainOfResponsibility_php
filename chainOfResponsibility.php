<?php
/**
 * The Chain of Responsibility Pattern.
 *
 * Gives the ability to chain object calls together.
 *
 * Allows for the client to make a request without having to know how that request is going to be handled.
 *
 * User: Rich
 * Date: 6/6/2015
 * Time: 12:40 AM
 */

/**
 * Class HomeChecker
 */
abstract class HomeChecker {

    protected $successor;

    public abstract function check(HomeStatus $home);

    public function succeedWith(HomeChecker $successor)
    {
        $this->successor = $successor;
    }

    public function next(HomeStatus $home)
    {
        if ($this->successor)
        {
            $this->successor->check($home);
        }
    }
}

/**
 * Class Locks
 */
class Locks extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->locked)
        {
            printf("The doors are not locked!! Abort abort.\n");
        }

        $this->next($home);
    }
}

/**
 * Class Lights
 */
class Lights extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->lightsOff)
        {
            printf("The lights are still on!! Abort abort.\n");
        }

        $this->next($home);
    }
}

/**
 * Class Alarm
 */
class Alarm extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->alarmOn)
        {
            printf("The alarm has not been set!! Abort abort.\n");
        }

        $this->next($home);
    }
}

/**
 * Class HomeStatus
 */
class HomeStatus {
    public $alarmOn = false;
    public $locked = true;
    public $lightsOff = false;
}

// create new instances
$locks = new Locks();
$lights = new Lights();
$alarm = new Alarm();

// set the successor chain
$locks->succeedWith($lights);
$lights->succeedWith($alarm);

// starts off here...
$locks->check(new HomeStatus());