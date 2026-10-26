export const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

/**
 * Inertia `title` callback. Public pages get their full title from the server
 * (SeoBuilder, so og:title matches); pass those through. Auth and admin pages
 * pass a bare title and get the brand appended once.
 */
export function pageTitle(title: string): string {
    if (!title) {
        return appName;
    }

    if (title === appName || title.endsWith(` - ${appName}`)) {
        return title;
    }

    return `${title} - ${appName}`;
}
