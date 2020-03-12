<?php


class JsonPath {

    private $_json;

    public function __construct($filePath) {
        return $this->_isValidJson($filePath);
    }

    public function jsonPath($path) {
        return $this->_jsonPath($path);
    }

    public function getJson() {
        return $this->_json;
    }

    private function _isValidJson($filePath) {
        if (!is_file($filePath)) {
            display("$filePath is not a file.", true);
        }

        $file = file_get_contents($filePath);
        $json = json_decode($file);

        if (json_last_error() !== 0) {
            display(json_last_error_msg(), true);
        }

        $this->_json = $json;
        return true;
    }

    private function _jsonPath($path) {

        // Example : //Articles
        if (preg_match_all('/^\/\/([\w-:]*)$/', $path, $matches)) {
            return $this->_getFirstItem($matches);
        }

        // Example : //Articles[title="My first article"] && //Articles[id=150] && //Articles[enabled=true] && //Articles/authors[name="Musso"] && //Articles/authors[position()=2] && //Articles/authors[last()]
        if (preg_match_all('/^\/\/([\w-\/:]*)\[([\w-():]*)([<>=\s-]*){0,1}"{0,1}([\w\s.-:]*)"{0,1}\]$/', $path, $matches)) {
            return $this->_getItemBySearch($matches);
        }

        // Example : //Articles/authors && //Articles/authors/identity && //Articles/authors/identity/firstname && more (Objects && Arrays)
        if (preg_match_all('/^\/\/([\w-:]*)\/(\/?[\w-\/:]*)\[?$/', $path, $matches)) {
            return $this->_getItems($matches);
        }

        // Example : //Articles[title="My first article"]/id && //Articles/authors[position()=2]/firstname && //Articles/authors[last()]/address/city
        if (preg_match_all('/^\/\/([\w-\/:]*)\[([\w-():]*)([<>=\s-]*){0,1}"{0,1}([\w\s.-:]*)"{0,1}\]\/([\w-\/:]*)$/', $path, $matches)) {
            return $this->_getItemValueBySearch($matches);
        }

        return '';
    }

    private function _getFirstItem($matches) {
        $param = $matches[1][0];
        return $this->_json->{$param};
    }

    private function _getItemBySearch($matches) {
        $param1 = $this->_getNestedProperty(implode('->', explode('/', $matches[1][0])));
        $key = $matches[2][0];
        $operator = trim($matches[3][0]);
        $search = $matches[4][0];

        if (!empty($param1)) {
            return $this->_handleSearchKey($key, $operator, $search, $param1);
        }

        return [];
    }

    private function _handleSearchKey($key, $operator, $search, $object) {
        switch ($key) {
            case 'position()':
                return $this->_handlePositionSearch($operator, $search, $object);
                break;
            case 'last()':
                return $this->_handleLastSearch($object);
                break;
            default:
                if (is_numeric($key)) { // TO HANDLE //Articles/authors[2] (= //Articles/authors[position()=2])
                    return $this->_handlePositionSearch('=', $key, $object);
                } else {
                    return $this->_handleDefaultSearch($key, $search, $object);
                }
                break;
        }
    }

    private function _handlePositionSearch($operator, $search, $object) {
        $operators = ['<', '<=', '>', '>='];
        if (in_array($operator, $operators)) {
            $searchValues = [];
            $i = 0;

            foreach ($object as $obj) {
                if ($operator === '<') {
                    if ($i < $search) {
                        array_push($searchValues, $obj);
                    }
                }

                if ($operator === '<=') {
                    if ($i <= $search) {
                        array_push($searchValues, $obj);
                    }
                }

                if ($operator === '>') {
                    if ($i > $search) {
                        array_push($searchValues, $obj);
                    }
                }

                if ($operator === '>=') {
                    if ($i >= $search) {
                        array_push($searchValues, $obj);
                    }
                }

                $i++;
            }

            return $searchValues;
        }

        if ($operator === '=') {
            return $object[$search];
        }

        return [];
    }

    private function _handleLastSearch($object) {
        return $object[count($object) - 1];
    }

    private function _handleDefaultSearch($key, $search, $object) {
        $values = [];

        foreach ($object as $value) {
            if (!empty($value->{$key}) && $value->{$key} === $search)
                $values[] = $value;
        }

        return (count($values) > 1) ? $values : $values[0];
    }

    private function _getItems($matches) {
        $param1 = $matches[1][0];
        $param2 = explode('/', $matches[2][0]);

        if (is_object($this->_json->{$param1})) {
            $args = '';

            foreach ($param2 as $param) {
                if (empty($args)) {
                    $args = "$param";
                } else {
                    $args .= "->$param";
                }

                if (!empty($this->_json->{$param1}->{$args}) && is_array($this->_json->{$param1}->{$args})) {
                    return $this->_json->{$param1}->{$args};
                }
            }

            return $this->_getNestedProperty($args, $this->_json->{$param1});
        }

        if (is_array($this->_json->{$param1})) {
            $param2 = implode('->', $param2);
            $values = [];

            foreach ($this->_json->{$param1} as $value) {
                if (!empty($value->{$param2}))
                    $values[] = $value->{$param2};
            }

            return $values;
        }

        return [];
    }

    private function _getItemValueBySearch($matches) {
        $object = $this->_getItemBySearch($matches);
        $lastMatchesItemKey = count($matches) - 1;

        if (is_object($object)) {
            return $this->_getNestedProperty(implode('->', explode('/', $matches[$lastMatchesItemKey][0])), $object);
        }

        if (is_array($object)) {
            $values = [];
            foreach ($object as $obj) {
                array_push($values, $this->_getNestedProperty(implode('->', explode('/', $matches[$lastMatchesItemKey][0])), $obj));
            }
            return $values;
        }

        return [];
    }

    private function _getNestedProperty($param, $obj = null) {
        $property = (!empty($obj)) ? $obj : $this->_json;

        foreach (explode('->', $param) as $item) {
            if (!empty($property->{$item}) || is_bool($property->{$item})) {
                $property = $property->{$item};
            } else {
                return [];
            }
        }

        return $property;
    }

}