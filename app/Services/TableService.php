<?php

namespace App\Services;

use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class TableService
{
    public function getAllTables(): Collection
    {
        return Table::orderBy('table_number')->get();
    }

    public function getTableById(int $id): Table
    {
        return Table::findOrFail($id);
    }
    
    public function updateStatus(Table $table, string $status): Table
    {
        $table->update(['status' => $status]);
        return $table;
    }
}
