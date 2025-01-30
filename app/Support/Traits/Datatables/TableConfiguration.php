<?php

namespace Support\Traits\Datatables;

trait TableConfiguration
{
    public int $index = 0;

    public function bootWithSorting(): void
    {
        $this->defaultSortColumn = 'id';
        $this->defaultSortDirection = 'desc';
    }

    public function initializeTableConfiguration(): void
    {
        $this->listeners = array_merge($this->listeners, [
           'refreshDatatable'  =>  'resetIndex'
        ]);
    }

    public function configure() : void
    {
        $this->setPrimaryKey('id');
        $this->setBulkActionsEnabled();

        $this->setHideBulkActionsWhenEmptyEnabled();

        $this->setBulkActions([
            'deleteSelected' => 'Delete Selected',
            'hideSelected' => 'Block Selected',
        ]);

        $this->setTableWrapperAttributes([
            'class' => 'table text-nowrap',
        ]);

        $this->setTrAttributes(function($row) {
                return [
                'class' => '',
                ];
        });

        $this->setThAttributes(function($row) {
                return [
                'class' => '',
                ];
        });

        $this->setTbodyAttributes([
                'class' => '',
        ]);
    }

    public function deleteSelected()
    {
        $this->emit('deleteAll',$this->getSelected());
    }
    public function hideSelected()
    {
        $this->emit('hideAll',$this->getSelected());
    }

    public function resetIndex()
    {
        $this->index = 0;
    }

}
