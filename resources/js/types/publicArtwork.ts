/** A width-described image set (`grid_640`, `grid_960`, ... grouped under `grid`). */
export interface ResponsiveImage {
    src: string;
    srcset: string;
}

/** `MediaUrlBuilder::public()`: public conversion URLs only, never the original. */
export interface PublicArtworkMediaUrls {
    thumb?: string;
    grid?: ResponsiveImage;
    display?: ResponsiveImage;
}

/** An artwork as `PublicArtworkResource` serializes it for public pages. */
export interface PublicArtwork {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    description_html: string | null;
    alt_text: string | null;
    /** Formatted as "F Y" (e.g. "March 2026"). */
    created_on: string | null;
    nsfw_flag: boolean;
    /** Server withheld the media (mature, no consent yet): gate it, never show the image. */
    media_withheld: boolean;
    /** Empty while `media_withheld`. */
    media_urls: PublicArtworkMediaUrls;
    tags?: { id: number; name: string; slug: string }[];
    galleries?: { id: number; name: string; slug: string }[];
}
