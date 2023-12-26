<?php


namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab; 
use Illuminate\Database\Eloquent\Builder;


class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'Published' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('Published', true)),
        'Un Published' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('Published', false)),
    ];
}
}