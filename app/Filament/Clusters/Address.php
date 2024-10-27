<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Address extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Address';
    protected static ?int $navigationSort = 0;
}
