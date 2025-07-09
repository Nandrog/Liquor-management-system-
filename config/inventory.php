<?php

// config/inventory.php

return [
/**
* A predefined list of allowed units of measure for inventory items.
* This ensures data consistency across the application.
*/
'units_of_measure' => [
'bottle',
'can',
'crate',
'kg',
'gram',
'liter',
'ml',
'unit', // For single items like caps or labels
],
];