<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use Sortable;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'threshold', 'parent_id', 'symbol'
    ];

    /**
     * @var array
     */
    public $sortable = [
        'id', 'name', 'description', 'threshold'
    ];

    /**
     * get all categories except the given id
     * @param $query
     * @param $id category id
     * @return mixed
     */
    public function scopeExceptGivenId($query, $id)
    {
        return $query->whereNotIn('id', [$id]);
    }

    /**
     * get all the categories orderd by asc or desc
     * @param $query
     * @param string $order
     * @return mixed
     */
    public function scopeGetAllCategories($query, $order)
    {
        return $query->orderBy('name', $order);
    }

    /**
     * get the parent category from the current category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    /**
     * get all subcategories/children from the category table
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany('App\Asset', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scenarios()
    {
        return $this->belongsToMany('App\Scenario', 'category_scenario', 'category_id', 'scenario_id')->using('App\CategoryScenario');
    }
}
