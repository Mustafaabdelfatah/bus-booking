<?php

namespace App\Support\Repositories\Main;

use App\Contracts\DB\EloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentMainRepository implements EloquentRepositoryInterface
{
    protected Model $modal;

    public function __construct(Model $model)
    {
        $this->modal = $model;
    }

    public function builder(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'desc',$orderCol = 'id',$orderByRaw = null): Builder
    {
        $query = $this->modal::with($relations)->where($condition)->select($cols);
        if(!$orderByRaw)
            $query = $query->orderBy($orderCol , $order);
        else
            $query = $query->orderByRaw($orderByRaw);

        return $query;

    }

    public function paginate(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc', string $orderCol = 'id', int|null $paginate = 10): LengthAwarePaginator
    {
        return $this->builder($cols, $relations,$condition, $order, $orderCol)->paginate($paginate ?? 10);
    }

    public function cursorPaginate(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc' , string $orderCol = 'id', int|null $paginate = 10, $orderByRaw = null ): CursorPaginator
    {
        return $this->builder($cols, $relations,$condition, $order , $orderCol , $orderByRaw)->cursorPaginate($paginate ?? 10);
    }

    public function all(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc', string $orderCol = 'id' , $orderByRaw = null): Collection
    {
        return $this->builder($cols, $relations,$condition, $order,$orderCol,$orderByRaw)->get();
    }

    public function store(array $data): ?Model
    {
        return $this->modal::create($data);
    }

    public function update(int $id, array $data): ?Model
    {

        $team = $this->findByCols(['id' => $id]);
        $team->update($data);
        return $team;
    }




    public function updateWithCondition(array $condition, array $data): ?Model
    {
        $team = $this->findByCols($condition);
        $team->update($data);
        return tap($team)->update($data);
    }

    public function updateWithReturn(int $id, array $data): ?Model
    {
        $team = $this->findByCols(['id' => $id]);
        return tap($team)->update($data);
    }

    public function findByCols(array $cols,array $with = [],array $select = ['*']): ?Model
    {
        return $this->modal::with($with)->select($select)->where($cols)->first();
    }

    public function find(array $cols , array $conditions): ?Model
    {
        return $this->modal::select($cols)->where($conditions)->first();
    }


    public function destroy(int $id): bool
    {
        return $this->modal::find($id)->delete();
    }

    public function destroyWithCondition(array $condition): bool
    {
        return $this->findByCols($condition)->delete();
    }


}
