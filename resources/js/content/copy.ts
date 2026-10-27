/**
 * Public-site marketing copy (#57): edit the words here, not in the page templates.
 *
 * Keyed by page. Data-driven text (titles and descriptions from the database),
 * form field labels, validation messages and UI chrome (nav, buttons, aria
 * labels) are not here. Meta descriptions live in app/Support/Seo/SeoBuilder.php.
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
};
