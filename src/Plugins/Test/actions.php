<?php

class  Test_actions {

    function __construct(&$pluginManager)
    {
        $pluginManager->register('test', $this, 'say_hello');
    }

    public function say_hello(){
        return '2321321';
    }
}