<?php
class MyExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return array('handler_temp' => new Twig_Filter_Method($this, 'handler_temp'),);
    }

    public function handler_temp($arg1)
    {
        return $arg1 - 273.15;
    }
}