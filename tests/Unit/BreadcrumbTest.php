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

    /** @test */
    public function can_add_items()
    {
        $this->assertEquals([
            [
                'label' => 'Dashboard',
                'href' => route('dashboard')
            ], [
                'label' => 'Profile',
                'active' => true
            ]
        ], Breadcrumb::items([
            [
                'label' => 'Profile',
                'active' => true
            ]
        ]));
    }
}
