<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(){
        $data =  [
                'hotels' => [
                    'default_varient' => 'show_hotel_image_right_side',
                    'variants' => [
                        [
                            'id' => 'show_hotel_image_left_side',
                            'name' => 'Show hotel image left side',
                            'description' => 'Show hotel image left side',
                        ],
                        [
                            'id' => 'show_hotel_image_right_side',
                            'name' => 'Show hotel image right side',
                            'description' => 'Show hotel image left side',
                        ],
                        [
                            'id' => 'show_hotel_details_in_table',
                            'name' => 'Show hotel detials in table format',
                            'description' => 'Show hotel detials in table format',
                        ],
                    ],
                ],
                'daywise_itnerary' => [
                    'default_varient' => 'default',
                    'variants' => [
                        [
                            'id' => 'default',
                            'name' => 'Default View',
                            'description' => 'Show daywise itnerary in default view',
                        ],
                        [
                            'id' => 'show_details_in_align',
                            'name' => 'Show details in align',
                            'description' => 'Show daywise itnerary image and details in align',
                        ],
                        [
                            'id' => 'show_daywise_itnerary_right_side',
                            'name' => 'Show daywise itnerary image right side',
                            'description' => 'Show daywise itnerary image right side and details in left side',
                        ],
                        [
                            'id' => 'show_daywise_itnerary_left_side',
                            'name' => 'Show daywise itnerary image left side',
                            'description' => 'Show daywise itnerary image left side and details in right side',
                        ],
                        [
                            'id' => 'show_daywise_itnerary_alternate_sides',
                            'name' => 'Show daywise itnerary image alternate sides',
                            'description' => 'Show daywise itnerary image alternate for each day',
                        ],
                    ],
                ],
                'package_preview' => [
                    'default_varient' => 'default',
                    'variants' => [
                        [
                            'id' => 'default',
                            'name' => 'Default View',
                            'description' => 'Show package preview in default view',
                        ],
                        [
                            'id' => 'show_details_in_table',
                            'name' => 'Show details in table',
                            'description' => 'Show package preview details in table format',
                        ],
                    ],
                ]
            ];
         return response()->json($data);
         // collect($collect['hotels']['variants'])->map->id,
    }
}
