<?php

/**
 * Class SqlBuilder
 *
 * @method insert()
 * @method select()
 * @method update()
 * @method delete()
 * @method truncate()
 */

namespace Core;

class SqlBuilder
{
    /**
     * @var null
     */
    private static $instance;
    /**
     * @var array
     */
    private $select = [];
    /**
     * @var array
     */
    private $from = [];
    /**
     * @var array
     */
    private $where = [];
    /**
     * @var array
     */
    private $insert = [];
    /**
     * @var array
     */
    private $update = [];

    /**
     * SqlBuilder constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return sqlBuilder
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param array $update
     *
     * @return $this
     */
    public function setUpdate(array $update)
    {
        $this->update = $this->filter($update);

        return $this;
    }

    /**
     * @param $array
     *
     * @return array
     */
    private function filter($array)
    {
        return array_filter(
            $array,
            function ($string) {
                return null !== $string && '' !== $string;
            }
        );
    }

    /**
     * @param string|array $select
     *
     * @return $this
     */
    public function setSelect($select)
    {
        if (is_string($select)) {
            $select = [$select];
        }
        $this->select = $this->filter($select);

        return $this;
    }

    /**
     * @param string|array $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        if (is_string($from)) {
            $from = [$from];
        }
        $this->from = $this->filter($from);

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return string
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (empty($this->from)) {
            throw new \Exception('Заполните метод setFrom.');
        }

        switch (strtolower($name)) {
            case 'insert':
                if (empty($this->insert)) {
                    throw new \Exception('Заполните метод setInsert.');
                }

                return 'INSERT INTO ' . $this->implode($this->from) . $this->getInsert();
            case 'select':
                return 'SELECT ' . (empty($this->select) ? '*' : $this->implode($this->select)) . ' FROM ' . $this->implode($this->from) . (empty($this->implodeWhere()) ? '' : ' WHERE ' . $this->implodeWhere());
            case 'update':
                if (empty($this->update)) {
                    throw new \Exception('Заполните метод getUpdate.');
                }

                return 'UPDATE ' . $this->implode($this->from) . $this->getUpdate() . (empty($this->implodeWhere()) ? '' : ' WHERE ' . $this->implodeWhere());
            case 'delete':
                return 'DELETE FROM ' . $this->implode($this->from) . (empty($this->implodeWhere()) ? '' : ' WHERE ' . $this->implodeWhere());
            case 'truncate':
                return 'TRUNCATE ' . $this->implode($this->from);
            default:
                throw new \Exception('Метод ' . $name . ' не найден.');
        }
    }

    /**
     * @param array $pieces
     *
     * @return string
     */
    private function implode(array $pieces)
    {
        return implode(
            ', ',
            array_map(
                function ($string) {
                    return '`' . $string . '`';
                },
                $pieces
            )
        );
    }

    /**
     * @return string
     */
    private function getInsert()
    {
        $output = [];
        foreach ($this->insert as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
                $value = 'NULL';
            }
            $output[$key] = $value;
        }

        return ' (`' . implode('`, `', array_keys($output)) . '`) VALUES (' . implode(', ', array_map(function ($string) {
            if ('NULL' === $string) {
                return $string;
            }

            return "'$string'";
        }, array_values($output))) . ')';
    }

    /**
     * @return string
     */
    private function implodeWhere()
    {
        $output = [];
        foreach ($this->where as $where) {
            if (
                is_array($where) &&
                count($where) === 3 &&
                count($this->filter($where)) === 3 &&
                in_array($where[1], ['=', '!=', '>', '<', '>=', '<='])
            ) {
                $output[] = '`' . $where[0] . '` ' . $where[1] . ' ' . (is_numeric($where[2]) ? $where[2] : "'" . $where[2] . "'");
            } elseif (is_string($where)) {
                $output[] = strtoupper($where);
            }
        }

        return implode(' ', $output);
    }

    /**
     * @return string
     */
    private function getUpdate()
    {
        $output = [];
        foreach ($this->update as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
                $value = 'NULL';
            } else {
                $value = "'$value'";
            }
            $output[] = "`$key` = $value";
        }

        return ' SET ' . implode(', ', $output);
    }

    /**
     * @param array $insert
     *
     * @return $this
     */
    public function setInsert(array $insert)
    {
        $this->insert = $insert;

        return $this;
    }

    /**
     * @param array $where
     *
     * @return $this
     */
    public function setWhere(array $where)
    {
        $this->where = $this->filter($where);

        return $this;
    }

}