/**
 * Public-site marketing copy (#57): edit the words here, not in the page templates.
 *
 * Keyed by page, plus `links` for call-to-action and "back" link labels shared
 * across pages. Data-driven text (titles and descriptions from the database),
 * form field labels, validation messages and UI chrome (nav, aria labels,
 * placeholders, filter controls, the "Commissions" CTA) are not here. Meta descriptions live in app/Support/Seo/SeoBuilder.php.
 */
export interface PublicCopy {
    site: {
        /** Wordmark in the header and footer. */
        name: string;
    };
    footer: {
        /** Follows "© <year> ". */
        copyright: string;
    };
    home: {
        /** The hero title renders as "<lead> <accent>", with the accent highlighted. */
        heroTitleLead: string;
        heroTitleAccent: string;
        /** Rendered as two lines. */
        heroIntro: readonly [string, string];
        featuredArtHeading: string;
        commissionHeading: string;
        commissionBlurb: string;
        featuredBooksHeading: string;
    };
    galleries: {
        heading: string;
        intro: string;
    };
    gallery: {
        emptyHeading: string;
        emptyBody: string;
    };
    artworkIndex: {
        heading: string;
        intro: string;
        emptyHeading: string;
        /** Shown when a tag filter matches nothing. */
        emptyTaggedBody: string;
        emptyBody: string;
    };
    artwork: {
        /** Under the commission button on an artwork page. */
        commissionBlurb: string;
    };
    books: {
        heading: string;
        intro: string;
        comingSoonHeading: string;
        comingSoonBody: string;
    };
    book: {
        chaptersHeading: string;
        availableAtHeading: string;
    };
    contact: {
        heading: string;
        intro: string;
        /** Shown after the message is sent. */
        success: string;
    };
    nsfwGate: {
        heading: string;
        body: string;
        revealButton: string;
        revealAllLabel: string;
    };
    links: {
        /** Home hero button to /art. */
        viewGalleries: string;
        /** Home featured art and /art header, to /artwork. */
        viewAllArtwork: string;
        /** Home featured books, to /books. */
        viewAllBooks: string;
        /** /artwork header, to /art. */
        browseGalleries: string;
        /** /artwork?tag= empty state, clears the filter. */
        seeAllArtwork: string;
        /** /books card hover button. */
        readMore: string;
        /** Lightbox link to the artwork page. */
        viewDetails: string;
        backToArtwork: string;
        backToGalleries: string;
        /** /books/{slug}, back to /books. */
        backToLibrary: string;
    };
}

export const copy: PublicCopy = {
    site: {
        name: 'Miri Spence',
    },
    footer: {
        copyright: 'Miri Spence. All rights reserved.',
    },
    home: {
        heroTitleLead: 'Art &',
        heroTitleAccent: 'Stories',
        heroIntro: [
            'The art and books of Miri Spence.',
            'Illustration, storytelling, and personal creative work.',
        ],
        featuredArtHeading: 'Featured Art',
        commissionHeading: 'Have a vision in mind?',
        commissionBlurb:
            "I am currently available for custom art commissions and narrative collaborations. Let's bring your ideas to life.",
        featuredBooksHeading: 'Featured Books',
    },
    galleries: {
        heading: 'Galleries',
        intro: 'Curated sets of themed illustrations and studies.',
    },
    gallery: {
        emptyHeading: 'No artworks yet',
        emptyBody: 'Check back soon for new additions to this gallery.',
    },
    artworkIndex: {
        heading: 'Artwork',
        intro: 'All of my recent illustrations and character art.',
        emptyHeading: 'No artwork found',
        emptyTaggedBody: 'Nothing is tagged with that yet.',
        emptyBody: 'Check back soon for new work.',
    },
    artwork: {
        commissionBlurb: 'Loved this style? Send a request to work together!',
    },
    books: {
        heading: 'Novels & Stories',
        intro: 'Exploring worlds through words.',
        comingSoonHeading: 'More coming soon',
        comingSoonBody:
            'New stories and illustrations are always in development.',
    },
    book: {
        chaptersHeading: 'Chapters',
        availableAtHeading: 'Available at',
    },
    contact: {
        heading: 'Get in Touch',
        intro: "Have a question or want to work together? I'd love to hear from you.",
        success: 'Message sent successfully!',
    },
    nsfwGate: {
        heading: 'Sensitive Content',
        body: 'This artwork may not be suitable for all audiences.',
        revealButton: 'Reveal Artwork',
        revealAllLabel: 'Show all NSFW artwork this session',
    },
    links: {
        viewGalleries: 'View Galleries',
        viewAllArtwork: 'View all artwork →',
        viewAllBooks: 'View All Books →',
        browseGalleries: 'Browse Galleries →',
        seeAllArtwork: 'See all artwork',
        readMore: 'Read More',
        viewDetails: 'View details →',
        backToArtwork: 'Back to Artwork',
        backToGalleries: 'Back to Galleries',
        backToLibrary: 'Back to Library',
    },
};
