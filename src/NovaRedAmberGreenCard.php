<?php

namespace Ontherocksoftware\NovaRedAmberGreenCard;

use Laravel\Nova\Card;

class NovaRedAmberGreenCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';




    public function __construct($monitorUuid)
    {
        return $this->withMeta([
            'globalApiKey' => config("nova-red-amber-green-card.rag_api_token", null),
            'monitorUuid' => $monitorUuid
        ]);
    }








    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-red-amber-green-card';
    }


    public function apiKey($apiKey)
    {
        return $this->withMeta(['apiKey' => $apiKey]);
    }
}
