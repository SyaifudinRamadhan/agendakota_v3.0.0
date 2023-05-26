<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
  
class PaginateArrayController extends Controller
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}