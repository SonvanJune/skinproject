<?php

namespace App\DTOs;

use App\Models\TrackingCode;
use App\Services\TrackingCodeService;
use Illuminate\Database\Eloquent\Collection;

class GetTrackingCodeDTO
{
    // Properties representing the details of a tracking_code
    public string $tracking_code_id;
    public string $tracking_code;
    public string $tracking_code_type;
    public string $tracking_code_language;
    public string $created_at;
    public string $updated_at;

    /**
     * Constructor to initialize the GetTrackingCodeDTO with tracking_code details.
     *
     * @param string $tracking_code_id - Unique identifier for the tracking_code.
     * @param string $tracking_code_text - The text content of the tracking_code.
     * @param string $created_at - Timestamp of when the tracking_code was created.
     * @param string $updated_at - Timestamp of when the tracking_code was last updated.
     */
    public function __construct(
        string $tracking_code_id,
        int $tracking_code_type,
        string $tracking_code,
        string $created_at,
        string $updated_at
    ) {
        $this->tracking_code_id = $tracking_code_id;
        $this->tracking_code_type = $tracking_code_type;

        switch ($tracking_code_type) {
            case TrackingCodeService::TRACKING_CODE_TYPE_HTML:
                $this->tracking_code_type = 'HTML';
                $this->tracking_code_language = 'xml';
                break;
            case TrackingCodeService::TRACKING_CODE_TYPE_CSS:
                $this->tracking_code_type = 'CSS';
                $this->tracking_code_language = 'css';
                break;
            case TrackingCodeService::TRACKING_CODE_TYPE_JAVASCRIPT:
                $this->tracking_code_type = 'Javascript';
                $this->tracking_code_language = 'javascript';
                break;
            default:
                $this->tracking_code_type = 'plain';
                $this->tracking_code_language = 'javascript';
        }

        $this->tracking_code = $tracking_code;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * Static method to create a GetTrackingCodeDTO instance from a Question model.
     *
     * @param TrackingCode $tracking_code - The Question model instance to convert into a DTO.
     * @return self - A new GetTrackingCodeDTO instance populated with data from the Question model.
     */
    public static function fromModel(TrackingCode $tracking_code): self
    {
        return new self(
            $tracking_code->tracking_code_id,
            $tracking_code->tracking_code_type,
            $tracking_code->tracking_code,
            $tracking_code->created_at,
            $tracking_code->updated_at
        );
    }

    /**
     * Static method to convert a collection of Question models into an array of GetTrackingCodeDTO instances.
     *
     * @param Collection|array $tracking_codes - A collection or array of Question model instances to be converted.
     * @return array - An array of GetTrackingCodeDTO instances created from the Question models.
     */
    public static function fromModels(Collection|array $tracking_codes): array
    {
        $result = [];

        foreach ($tracking_codes as $tracking_code) {
            $result[] = self::fromModel($tracking_code);
        }

        return $result;
    }
}
