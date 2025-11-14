<?php

namespace App\DTOs;

use App\Models\SlideshowImage;

/**
 * Data Transfer Object for creating a slideshow image.
 *
 * This class represents the structure of data used when creating a new slideshow image.
 * It helps to encapsulate the relevant fields for creating a slideshow image, ensuring a clear separation
 * of business logic and data structure.
 */
class UpdateSlideshowImageDTO
{
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
     * CreateSlideshowImageDTO constructor.
     *
     * Initializes the DTO with the necessary data for creating a slideshow image.
     *
     * @param string $slideshow_image_url The URL of the slideshow image.
     * @param int $slideshow_image_index The index/position of the image.
     * @param string $slideshow_image_alt The alt text for the image.
     */
    public function __construct(string $slideshow_image_url, int $slideshow_image_index, string $slideshow_image_alt)
    {
        $this->slideshow_image_url = $slideshow_image_url;
        $this->slideshow_image_index = $slideshow_image_index;
        $this->slideshow_image_alt = $slideshow_image_alt;
    }

    /**
     * Create a new instance of the DTO from the SlideshowImage model.
     *
     * This static method helps to easily convert a SlideshowImage model into a DTO,
     * making it easier to transfer the necessary data between different layers of the application.
     *
     * @param SlideshowImage $slideshowImage The slideshow image model from the database.
     * @return self Returns an instance of CreateSlideshowImageDTO.
     */
    public static function fromModel(SlideshowImage $slideshowImage): self
    {
        return new self(
            $slideshowImage->slideshow_image_url,
            $slideshowImage->slideshow_image_index,
            $slideshowImage->slideshow_image_alt
        );
    }
}
