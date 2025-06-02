<?php
// Define categories with subcategories
$categories = [
    'Frozen' => [
        'range' => [1000, 1999],
        'subcategories' => [
            'Frozen Meals' => [1000, 1001],
            'Frozen Meat' => [1002, 1002],
            'Frozen Seafood' => [1003, 1003],
            'Ice Cream' => [1004, 1005]
        ]
    ],
    'Home' => [
        'range' => [2000, 2999],
        'subcategories' => [
            'Medicines' => [2000, 2001],
            'Cleaning Products' => [2002, 2006],
            'Household Items' => [2003, 2005]
        ]
    ],
    'Fresh' => [
        'range' => [3000, 3999],
        'subcategories' => [
            'Dairy' => [3000, 3001],
            'Meat' => [3002, 3002],
            'Fruits' => [3003, 3007]
        ]
    ],
    'Beverages' => [
        'range' => [4000, 4999],
        'subcategories' => [
            'Tea' => [4000, 4002],
            'Coffee' => [4003, 4004],
            'Snacks' => [4005, 4005]
        ]
    ],
    'Pet-food' => [
        'range' => [5000, 5999],
        'subcategories' => [
            'Dog Food' => [5000, 5001],
            'Bird Food' => [5002, 5002],
            'Cat Food' => [5003, 5003],
            'Fish Food' => [5004, 5004]
        ]
    ]
];
?>