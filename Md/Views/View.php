<?php

namespace Md\Views;

use Md\Http\Http;

class View implements IView
{
    static protected array $vars = [];

    public static function getVar(string $_name, $_default = null)
    {
        return self::$vars[$_name] ?? $_default;
    }

    public static function setVar(string $_name, $_value)
    {
        self::$vars[$_name] = $_value;
    }

    protected string $path;

    protected string $file;

    protected array $childs;

    public function __construct(string $_path)
    {
        $this->path = $_path;
        $this->file = 'index';
        $this->childs = [];
    }

    public function setFile(string $_filename): IView
    {
        $this->file = ($this->path.$_filename.'.php');
        // echo ('<pre>'.var_export($this, true));
        if(!\is_file($this->file)) {
            Http::notFound('invalid view ('.$_filename.')');
        }

        return $this;
    }

    public function fetch(array $_vars = []) : string
    {
        foreach($this->childs as $k => $v) {
            $_vars[$k] = $v->fetch($_vars);
        }

        \ob_start();
        \extract($_vars);
        \extract(self::$vars, EXTR_SKIP);
        require ($this->file);
        return \ob_get_clean();
    }

    public function setChild(string $_key, string $_filename): IView
    {
        $v = new self($this->path);
        $v->setFile($_filename);
        $this->childs[$_key] = $v;
        return $v;
    }
}
