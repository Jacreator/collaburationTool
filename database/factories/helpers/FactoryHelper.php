<?php

namespace Database\Factories\Helpers;

class FactoryHelper
{
    /**
     * Get Model id
     * 
     * @param string | HasFactory $model this is a database model
     * 
     * @return string
     */
    public static function getRandomModelId(string $model)
    {
        $countPost = $model::count();
        // get random post model
        if ($countPost === 0) {
            return $model::factory()->create()->id;
        } else {
            return $model::inRandomOrder()->first()->id;
        }
    }
}
