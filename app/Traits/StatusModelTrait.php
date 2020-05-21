<?php

namespace App\Traits;

trait StatusModelTrait
{
    public function scopeActive($query)
    {
        return $query->where('archived',false)->where('deleted_at',null);
    }

    public function isActive()
    {
        return !$this->isArchived() && !$this->isDeleted();
    }

    public function isArchived()
    {
        return (boolean)$this->archived;
    }

    public function isDeleted()
    {
        if($this->deleted_at){
            return true;
        }
        return false;
    }

    public function isCanceled()
    {
        return $this->state == $this::STATUS_CANCEL;
    }

    public function getStatusTextAttribute()
    {
        if($this->isDeleted()){
            return [
                'text' => __('admin.status.delete'),
                'color' => 'red',
            ];
        }else if($this->isArchived()){
            return [
                'text' => __('admin.status.archive'),
                'color' => 'blue',
            ];
        }
        return [
            'text' => __('admin.status.active'),
            'color' => 'green',
        ];
    }

    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

    public function getIsArchivedAttribute()
    {
        return $this->isArchived();
    }

    public function getIsDeletedAttribute()
    {
        return $this->isDeleted();
    }

    public function getIsCanceledAttribute()
    {
        return $this->isCanceled();
    }
    
}