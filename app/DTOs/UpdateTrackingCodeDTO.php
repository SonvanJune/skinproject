<?php

namespace App\DTOs;

use App\Models\TrackingCode;

class UpdateTrackingCodeDTO
{
    /**
     * UpdateTrackingCodeDTO constructor.
     *
     * @param string $tracking_code The tracking code.
     * @param int $tracking_code_type The tracking code type.
     */

    /** @var string $tracking_code */
    public string $tracking_code;

    /** @var int $tracking_code_type */
    public int $tracking_code_type;

    /*
    * Create a new UpdateTrackingCodeDTO
    * @param array $data The data to create the tracking code.
    */
    public function __construct(array $data)
    {
        $this->tracking_code = $data['tracking_code'];
        $this->tracking_code_type = $data['tracking_code_type'];
    }

    /*
    * Create a new UpdateTrackingCodeDTO from a model
    * @param TrackingCode $trackingCode The tracking code model
    * @return UpdateTrackingCodeDTO The new UpdateTrackingCodeDTO
    */
    public static function fromModel(TrackingCode $trackingCode): self
    {
        return new self([
            'tracking_code' => $trackingCode->tracking_code,
            'tracking_code_type' => $trackingCode->tracking_code_type,
        ]);
    }
}
