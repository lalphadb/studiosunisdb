<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class RecentActivity extends Widget
{
    protected static string $view = 'filament.admin.widgets.recent-activity';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = null; // Pas de polling
}
