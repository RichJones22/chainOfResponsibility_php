<?php
/**
 * The Chain of Responsibility Pattern Example.
 *
 * Created by PhpStorm.
 * User: Rich
 * Date: 6/6/2015
 * Time: 12:40 AM
 */

/**
 * HomeChecker is the brains behind this pattern.
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



class Locks extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->locked)
        {
            throw new Exception('The doors are not locked!! Abort abort.');
        }

        $this->next($home);
    }
}

class Lights extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->lightsOff)
        {
            throw new Exception('The lights are still on!! Abort abort.');
        }

        $this->next($home);
    }
}

class Alarm extends HomeChecker {
    public function check(HomeStatus $home)
    {
        if (!$home->alarmOn)
        {
            throw new Exception('The alarm has not been set!! Abort abort.');
        }

        $this->next($home);
    }
}

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