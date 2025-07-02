<?php

return [
    'recipes' => [
        // Recipe for 1 bottle of Uganda Waragi 750ml
        'FG-UWG-750' => [
            'output_product_sku' => 'FG-UWG-750',
            'output_quantity' => 1,
            'materials' => [
                // SKU of raw material => quantity needed per bottle
                'RM-MOL-01' => 4.5,    // 4.5 kg of Molasses
                // 'WATER' => 18,    // We'll assume water is plentiful and not tracked in inventory for now
                //'RM-CASS-01' => 0.005, // 5g of Cassava Flour (converted to kg)
                'RM-YST-01' => 0.015, // 15g of Yeast (converted to kg)
                'RM-CIT-P-01' => 0.005, // 5g of Citrus Peels (converted to kg)
                'RM-CHR-01' => 0.010, // 10g of Charcoal (converted to kg)
                'RM-BOT-750' => 1,     // 1 Bottle
                'RM-CAP-01' => 1,     // 1 Cap
                'RM-LBL-01' => 1,     // 1 Label
            ],
        ],
    ],
];