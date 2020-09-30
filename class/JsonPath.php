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

    // TODO : Prettify errors
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
        if (preg_match_all('/\/{0,2}([\w\p{L}-\/:]+)(\[?([\w\p{L}-():]*)\s*([<>=-]*)?\s*"?([\w\s\p{L}.-:]*)"?\])?/', $path, $matches, PREG_SET_ORDER)) {
            return $this->_handleMatchResult($matches);
        }

        return '';
    }

    private function _handleMatchResult($matches) {
        $resultObject = $this->_json;

        foreach ($matches as $match) {
            $resultObject = $this->_getNestedProperty(implode('->', explode('/', $match[1])), $resultObject);

            if (!empty($match[2])) {
                $key = $match[3];
                $operator = $match[4];
                $search = $match[5];

                $resultObject = $this->_handleSearchKey($key, $operator, $search, $resultObject);
            }
        }

        return $resultObject;
    }

    private function _handleSearchKey($key, $operator, $search, $object) {
        switch ($key) {
            case 'position()':
                return $this->_handlePositionSearch($operator, $search, $object);
            case 'last()':
                return $this->_handleLastSearch($object);
            case 'first()':
                return $this->_handleFirstSearch($object);
            default:
                if (is_numeric($key)) { // TO HANDLE //Articles/authors[2] (= //Articles/authors[position()=2])
                    return $this->_handlePositionSearch('=', $key, $object);
                } else {
                    return $this->_handleDefaultSearch($key, $search, $object);
                }
        }
    }

    private function _handlePositionSearch($operator, $search, $object) {
        $operators = ['<', '<=', '>', '>='];
        if (in_array($operator, $operators)) {
            $searchValues = [];
            $i = 0;

            foreach ($object as $obj) {
                if ($operator === '<' && $i < $search) {
                    array_push($searchValues, $obj);
                }

                if ($operator === '<=' && $i <= $search) {
                    array_push($searchValues, $obj);
                }

                if ($operator === '>' && $i > $search) {
                    array_push($searchValues, $obj);
                }

                if ($operator === '>=' && $i >= $search) {
                    array_push($searchValues, $obj);
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

    private function _handleFirstSearch($object) {
        return $object[0];
    }

    private function _handleDefaultSearch($key, $search, $object) {
        $values = [];

        foreach ($object as $value) {
            if (!empty($value->{$key}) && $value->{$key} == $search) {
                $values[] = $value;
            }
        }

        return (count($values) > 1) ? $values : $values[0];
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