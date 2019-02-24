<?php

/**
 * Class Datatables_server
 *
 * @property CI_DB_query_builder $db
 */
class Datatables_server
{
    private $_columns = array();
    private $_trashed = 'without';
    private $_deleted_at_field = 'deleted_at';

    protected $_table = '';
    protected $_table_join = array();
    protected $_paging = true;
    protected $_currentPage = 1;
    protected $_limit = 10;
    protected $_offset = 0;
    protected $_request = [];
    protected $_sortField = 'name';
    protected $_sortDir = 'ASC';
    protected $_search = '';
    protected $_filters = [];
    protected $_filterBranch = false;

    private function _dataOutput ($data)
    {
        $out = array();
        for ( $i = 0, $ien = count($data) ; $i < $ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($this->_columns) ; $j<$jen ; $j++ ) {
                $column = $this->_columns[$j];
                // Is there a formatter?
                if (isset($column['bt'])) {
                    if ( isset( $column['formatter'] ) ) {
                        $row[ $column['bt'] ] = $column['formatter']( $data[$i][ $column['bt'] ], $data[$i] );
                    }
                    else {
                        $row[ $column['bt'] ] = $data[$i][ $this->_columns[$j]['bt'] ];
                    }
                }
                if (isset($column['addt'])) {
                    $row[ $column['addt'] ] = $column['value'];
                }
            }
            $out[] = $row;
        }
        return $out;
    }

    private function _pluck($prop = 'bt', $columns = false)
    {
        $out = array();
        $columns = !$columns ? $this->_columns : $columns;

        foreach ($columns as $column) {
            if ( isset($column['db']) && isset($column['bt']) ) {
                if ($prop === 'alias') {
                    $out[] = $column['db'].' AS '.$column['bt'];
                } else {
                    $out[] = $column[$prop];
                }
            }
        }

        return $out;
    }

    private function _order()
    {
        if (!empty($this->_sortField) && !empty($this->_sortDir))
        {
            $btColumns = $this->_pluck('bt');
            $columnIdx = array_search($this->_sortField, $btColumns);

            $column = $this->_columns[$columnIdx];

            ci()->db->order_by($column['db'], $this->_sortDir);
        }
    }

    public function withTrashed()
    {
        $this->_trashed = 'with';
        return $this;
    }

    public function withoutTrashed()
    {
        $this->_trashed = 'without';
        return $this;
    }

    public function onlyTrashed()
    {
        $this->_trashed = 'only';
        return $this;
    }

    private function _whereTrashed()
    {
        $prefix = ci()->db->dbprefix;
        ci()->db->group_start();
        switch($this->_trashed)
        {
            case 'only' :
                ci()->db->where($prefix.$this->_table.'.'.$this->_deleted_at_field.' IS NOT NULL', NULL, FALSE);
                break;
            case 'without' :
                ci()->db->where($prefix.$this->_table.'.'.$this->_deleted_at_field.' IS NULL', NULL, FALSE);
                break;
            case 'with' :
                break;
        }
        ci()->db->group_end();

        return $this;
    }

    public function setColumns($columns = array())
    {
        $this->_columns = $columns;
        return $this;
    }

    public function filter($field, $operator, $value)
    {
        $btColumns = $this->_pluck();
        $columnIdx = array_search($field, $btColumns);
        
        $column = $this->_columns[$columnIdx];

        if ($operator == 'contains') {
            ci()->db->or_like($column['db'], $value);
        }
        else if ($operator == 'in') {
            ci()->db->where_in($column['db'], $value);
        }
        else if ($operator == 'gte') {
            ci()->db->where($column['db'] .' >=', $value);
        }
        else if ($operator == 'lte') {
            ci()->db->where($column['db']. ' <=', $value);
        }
        else if ($operator == 'is') {
            if ($value === NULL) {
                ci()->db->where($column['db']. ' IS NULL');
            } else {
                ci()->db->where($column['db']. ' IS', $value);
            }
        }
        else {
            ci()->db->where($column['db'], $value);
        }
    }

    public function addFilterRequest($field, $value, $request, $operator = 'eq')
    {
        $filter = (object) [
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        ];

        $request['filters'][] = $filter;
        return $request;
    }

    private function search()
    {
        $btColumns = $this->_pluck('db');
        ci()->db->group_start();
        foreach ($btColumns as $item) 
        {
            $toArray = explode('.', $item);
            $table = $toArray[0];
            if ($table === $this->_table) {
                $item = ci()->db->dbprefix($table) . '.' . $toArray[1];
            }

            $escape = ci()->db->escape_str($this->_search);
            ci()->db->or_like($item, $escape, 'both', FALSE);
        }
        ci()->db->group_end();
    }

    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }

    public function setTableJoin($table, $condition, $columns = array(), $type = '')
    {
        $this->_table_join[] = array(
            'table' => $table,
            'type' => $type,
            'condition' => $condition,
            'columns' => $columns
        );

        return $this;
    }

    public function clearTableJoin()
    {
        $this->_table_join[] = array();
        return $this;
    }

    private function _getRows()
    {
        ci()->db->select($this->_pluck('alias'));

        if ($this->_paging) {
            ci()->db->limit($this->_limit, $this->_offset);
        }

        if (!empty($this->_sortField) && !empty($this->_sortDir)) {
            $this->_order();
        }

        if (!empty($this->_search)) {
            $this->search();
        }

        if ($this->_filters) {
            foreach ($this->_filters as $filter) {
                if (is_string($filter)) {
                    $filter = json_decode($filter);
                }
                
                $this->filter($filter->field, $filter->operator, $filter->value);
            }
        }

        if ($this->_trashed !== 'with') {
            $this->_whereTrashed();
        }

        if ($this->_table_join)
        {
            foreach ($this->_table_join as $key => $item) {
                ci()->db->join(
                    $item['table'].' AS '.$item['table'].$key,
                    str_replace($item['table'], $item['table'].$key, $item['condition']),
                    $item['type']
                );
            }
        }

        if ($this->_filterBranch) {
            ci()->db->group_start()
                ->where_in('branch_id', [$this->_filterBranch])
                ->group_end();
        }

        $result = ci()->db->get($this->_table)->result_array();

        return $this->_dataOutput($result);
    }

    private function _getTotal()
    {
        if (!empty($this->_search)) {
            $this->search();
        }

        if ($this->_filters) {
            foreach ($this->_filters as $filter) {
                if (is_string($filter)) {
                    $filter = json_decode($filter);
                }

                $this->filter($filter->field, $filter->operator, $filter->value);
            }
        }

        if ($this->_table_join)
        {
            foreach ($this->_table_join as $key => $item) {
                ci()->db->join(
                    $item['table'].' AS '.$item['table'].$key,
                    str_replace($item['table'], $item['table'].$key, $item['condition']),
                    $item['type']
                );
            }
        }

        if ($this->_trashed !== 'with') {
            $this->_whereTrashed();
        }

        if ($this->_filterBranch) {
            ci()->db->group_start()
                ->where_in('branch_id', [$this->_filterBranch])
                ->group_end();
        }

        $total = ci()->db->count_all_results($this->_table);

        return (int) $total;
    }

    public function setRequest($request)
    {
        $this->_request = $request;
        if (isset($request['limit']) && isset($request['limit']) && isset($request['page'])) {
            $this->_paging = true;
            $this->_limit = $request['limit'];
            $this->_currentPage = $request['page'];
            $this->_offset = $this->_limit * ($this->_currentPage - 1);
        } else {
            $this->_paging = false;
        }

        if ($request['limit'] <= 0) {
            $this->_paging = false;
        }

        if (isset($request['search'])) {
            $this->_search = $request['search'];
        }
        if (isset($request['sort']) && isset($request['order'])) {
            $this->_sortField = $request['sort'];
            $this->_sortDir = $request['order'];
        }

        if (isset($request['filters'])) {
            $this->_filters = $request['filters'];
        }

        return $this;
    }

    public function process($request, $columns, $table, $filterBranch = false)
    {
        if ($columns) {
            $this->_columns = $columns;
        }

        $this->_filterBranch = $filterBranch;
        $this->setRequest($request);
        $this->setTable($table);

        if ($this->_table_join)
        {
            foreach ($this->_table_join as $key => $item)
            {
                if ($item['columns'])
                {
                    $columns = $item['columns'];
                    foreach ($columns as $keyColumn => $column)
                    {
                        $columns[$keyColumn] = [
                            'db' => str_replace($item['table'], $item['table'].$key, $column['db']),
                            'bt' => $column['bt']
                        ];
                        if (isset($column['formatter'])) {
                            $columns[$keyColumn]['formatter'] = $column['formatter'];
                        }
                    }
                    $this->_columns = array_merge($this->_columns, $columns);
                }
            }
        }

        $totalData = $this->_getTotal();
        $numPages = (int)ceil($totalData / $this->_limit);
        $from = $this->_offset + 1;
        $to = $from + $this->_limit - 1;

        return [
            'total'         => $totalData,
            'per_page'      => (int) $this->_limit,
            'current_page'  => (int) $this->_currentPage,
            'last_page'     => $numPages,
            'from'          => $from,
            'to'            => $to,
            'rows'          => $this->_getRows()
        ];
    }
}