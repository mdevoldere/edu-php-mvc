<?php 

namespace MD\Views;

interface IView 
{
    public function setFile(string $_filename): IView;

    public function fetch(array $_data): string;

    public function setChild(string $key, string $_filename): IView;
}