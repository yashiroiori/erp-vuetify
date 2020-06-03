<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilderParserTrait
{    
    public function scopeFilter($builder, $request)
    {
        $qb = $request->input('query');
        $group = [];
        $rows = $this::query()->withTrashed();
        $method = !isset($qb['logicalOperator']) || $qb['logicalOperator'] === 'all' ? 'where' : 'orWhere';
        if(isset($qb['children'])){
            $group['query'] = ['children' => $qb['children']];
            $group['query']['logicalOperator'] = $qb['logicalOperator'];
            $this->parseQBGroup($rows, $group, $method);
        }
        return $rows;
    }

    function parseQBGroup($query, $group, $method = 'where')
    {
        $query->{$method}(function ($subquery) use ($group) {
            $sub_method = $group['query']['logicalOperator'] === 'all' ? 'where' : 'orWhere';
            foreach ($group['query']['children'] as $child) {
                if ($child['type'] === 'query-builder-group') {
                    $this->parseQBGroup($subquery, $child, $sub_method);
                } else {
                    $this->parseQBRule($subquery, $child, $sub_method);
                }
            }
        });
    }

    function parseQBRule($query, $rule, $method)
    {
        if($rule['query']['rule'] == 'status'){
            switch($rule['query']['value']){
                case 'withTrashed':
                    $query->withTrashed();
                break;
                case 'active':
                    $query->{$method}('archived', 0)->{$method}('deleted_at', null);
                break;
                case 'archived':
                    $query->{$method}('archived', 1);
                break;
                case 'trashed':
                    $query->onlyTrashed();
                break;
                default:
                    $query->{$method}('archived',0);
                break;
            }
        }else{
            switch($rule['query']['operator']){
                case 'equals':
                case '=':
                    if($rule['query']['rule'] == 'created_at'){
                        $query->{$method}($rule['query']['rule'], 'LIKE', $rule['query']['value'].'%');
                    }else{
                        $query->{$method}($rule['query']['rule'], '=', $rule['query']['value']);
                    }
                break;
                case 'does not equal':
                case '<>':
                    $query->{$method}($rule['query']['rule'], '<>', $rule['query']['value']);
                break;
                case 'contains':
                    $query->{$method}($rule['query']['rule'], 'LIKE', '%'.$rule['query']['value'].'%');
                break;
                case 'does not contain':
                    $query->{$method}($rule['query']['rule'], 'NOT LIKE', '%'.$rule['query']['value'].'%');
                break;
                case 'is empty':
                    $query->{$method}($rule['query']['rule'], '=', '');
                break;
                case 'is not empty':
                    $query->{$method}($rule['query']['rule'], '<>', '');
                break;
                case 'is null':
                    $query->{$method.'Null'}($rule['query']['rule']);
                break;
                case 'is no null':
                    $query->{$method.'NotNull'}($rule['query']['rule']);
                break;
                case 'begins with':
                    $query->{$method}($rule['query']['rule'], 'LIKE', $rule['query']['value'].'%');
                break;
                case 'ends with':
                    $query->{$method}($rule['query']['rule'], 'LIKE', '%'.$rule['query']['value']);
                break;
                case '<=':
                    $query->{$method}($rule['query']['rule'], '<=', $rule['query']['value']);
                break;
                case '>=':
                    $query->{$method}($rule['query']['rule'], '>=', $rule['query']['value']);
                break;
            }
        }
        // if ($rule['query']['rule'] === 'age') {
        //     $query->{$method}('age', $rule['query']['selectedOperator'], $rule['query']['value']);
        // }
        // if ($rule['query']['rule'] === 'job_title') {
        //     $query->{$method}('title', $rule['query']['selectedOperator'], $rule['query']['value']);
        // }
    }
}