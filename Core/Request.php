<?php

namespace Core;

class Request {
    public function __construct() {
        $this->bootstrapSelf();
    }

    public function getParams() {
        if($this->requestMethod === "GET")
        {
            $params = [];
            $pattern = $this->resolveRoute($_SERVER['REQUEST_URI']);
            $params = [
                'id' => $pattern[2] ?: null
            ];
            return $params;
        }
        else if ($this->requestMethod === "POST")
        {
            $params = [];

            foreach($_POST as $key => $value)
            {
                $params[$key] = filter_input(INPUT_POST, $key);
            }

            $pattern = $this->resolveRoute($_SERVER['REQUEST_URI']);
            if(isset($pattern[2])) {
                $params['id'] = $pattern[2];
            }

            return $params;
        }
        else if($this->requestMethod == "DELETE") {
            $params = [];
            $pattern = $this->resolveRoute($_SERVER['REQUEST_URI']);
            $params = [
                'id' => $pattern[2] ?: null
            ];
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

    private function resolveRoute($uri) {
        $matches = [];
        preg_match('/\/(\w+)\/(\d+)/', $uri, $matches);

        return $matches;
    }
}