<?php

namespace Tests\Unit;

use App\Models\Breadcrumb;
use Tests\TestCase;

class BreadcrumbTest extends TestCase
{
    /** @test */
    public function has_dashboard_link()
    {
        $this->assertEquals([
            [
                'label' => 'Dashboard',
                'href' => route('dashboard')
            ]
        ], Breadcrumb::items());
    }
}
