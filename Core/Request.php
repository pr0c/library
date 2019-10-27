<?php

namespace Core;

class Request implements IRequest {
    public function __construct() {
        $this->bootstrapSelf();
    }

    public function getParams() {
        if($this->requestMethod === "GET")
        {
            return;
        }
        if ($this->requestMethod === "POST")
        {
            $params = array();
            foreach($_POST as $key => $value)
            {
                $params[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            return $params;
        }
    }

    private function bootstrapSelf() {
        foreach($_SERVER as $param => $value) {
            $this->{$this->toCamelCase($param)} = $value;
        }
    }

    private function toCamelCase($string) {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);
        foreach($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
        return $result;
    }
}