<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Services\TableService;
use App\Http\Resources\TableResource;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct(
        protected TableService $tableService
    ) {}

    public function index()
    {
        $tables = $this->tableService->getAllTables();
        return TableResource::collection($tables);
    }
    
    public function show(Table $table)
    {
        return new TableResource($table);
    }
}