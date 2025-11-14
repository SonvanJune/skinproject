<?php

namespace App\DTOs;

use App\Models\SlideshowImage;

/**
 * Data Transfer Object for retrieving a list of slideshow images.
 *
 * This class helps encapsulate the necessary fields for representing a slideshow image
 * and provides utility methods to convert models into DTOs.
 */
class GetListSlideShowDTO
{
     /**

     * @var string The id of the slideshow image.
     */
    public string $slideshow_image_id;
    /**

     * @var string The URL of the slideshow image.
     */
    public string $slideshow_image_url;

    /**
     * @var int The index/position of the slideshow image in the slideshow sequence.
     */
    public int $slideshow_image_index;

    /**
     * @var string The alt text for the slideshow image (used for accessibility and SEO).
     */
    public string $slideshow_image_alt;

    /**
     * GetListSlideshowImageDTO constructor.
     *
     * Initializes the DTO with the necessary data for a single slideshow image.
     *
     * @param string $slideshow_image_url The URL of the slideshow image.
     * @param int $slideshow_image_index The index/position of the image.
     * @param string $slideshow_image_alt The alt text for the image.
     */
    public function __construct(string $slideshow_image_id, string $slideshow_image_url, int $slideshow_image_index, string $slideshow_image_alt)
    {
        $this->slideshow_image_id = $slideshow_image_id;
        $this->slideshow_image_url = $slideshow_image_url;
        $this->slideshow_image_index = $slideshow_image_index;
        $this->slideshow_image_alt = $slideshow_image_alt;
    }

    /**
     * Converts a SlideshowImage model into a GetListSlideshowImageDTO.
     *
     * This method simplifies the conversion of a single SlideshowImage model into its corresponding DTO.
     *
     * @param SlideshowImage $slideshowImage The slideshow image model from the database.
     * @return self Returns an instance of GetListSlideshowImageDTO.
     */
    public static function fromModel(SlideshowImage $slideshowImage): self
    {
        return new self(
            $slideshowImage->slideshow_image_id,
            $slideshowImage->slideshow_image_url,
            $slideshowImage->slideshow_image_index,
            $slideshowImage->slideshow_image_alt
        );
    }

    /**
     * Converts an array of SlideshowImage models into an array of GetListSlideshowImageDTOs.
     *
     * This method is used to convert a collection of slideshow image models into DTOs for easier handling and transfer.
     *
     * @param array $slideshowImages An array of SlideshowImage models.
     * @return array Returns an array of GetListSlideshowImageDTO instances.
     */
    public static function fromModels(array $slideshowImages): array
    {
        $result = [];
        foreach ($slideshowImages as $slideshowImage) {
            $result[] = new self(
                 $slideshowImage->slideshow_image_id,
                $slideshowImage->slideshow_image_url,
                $slideshowImage->slideshow_image_index,
                $slideshowImage->slideshow_image_alt
            );
        }
        return $result;
    }
}
