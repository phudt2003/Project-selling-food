<?php

if (! function_exists('product_image_url')) {
    function product_image_url($image): string
    {
        $imageName = basename((string) $image);

        if ($imageName && file_exists(public_path('uploads/product/' . $imageName))) {
            return asset('uploads/product/' . $imageName);
        }

        return asset('uploads/product/bapcai50.jpg');
    }
}
