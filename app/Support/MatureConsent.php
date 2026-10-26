<?php

namespace App\Support;

use App\Models\Artwork;
use Illuminate\Contracts\Session\Session;

/**
 * The visitor's consent to see mature (nsfw_flag) artwork, kept in the Laravel
 * session: a set of revealed Artwork IDs plus a "show all" flag. It lives as
 * long as the session does. See ADR-0004.
 */
final class MatureConsent
{
    private const IDS_KEY = 'mature_consent.ids';

    private const ALL_KEY = 'mature_consent.all';

    public function __construct(private readonly Session $session) {}

    /**
     * Whether this artwork's media URLs may be shown to the visitor.
     */
    public function allows(Artwork $artwork): bool
    {
        if (! $artwork->nsfw_flag) {
            return true;
        }

        return $this->showsAll() || in_array($artwork->getKey(), $this->revealedIds(), true);
    }

    public function showsAll(): bool
    {
        return (bool) $this->session->get(self::ALL_KEY, false);
    }

    /**
     * @return array<int, int>
     */
    public function revealedIds(): array
    {
        return array_map('intval', (array) $this->session->get(self::IDS_KEY, []));
    }

    /**
     * Reveal one artwork. Ignored unless it exists, is published and is flagged.
     */
    public function reveal(int $artworkId): void
    {
        $eligible = Artwork::published()
            ->where('nsfw_flag', true)
            ->whereKey($artworkId)
            ->exists();

        if (! $eligible || in_array($artworkId, $this->revealedIds(), true)) {
            return;
        }

        $this->session->put(self::IDS_KEY, [...$this->revealedIds(), $artworkId]);
    }

    public function revealAll(): void
    {
        $this->session->put(self::ALL_KEY, true);
    }
}
