import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/galleries',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:13
 * @route '/galleries'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
export const show = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/galleries/{gallery}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
show.url = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { gallery: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
            args = { gallery: args.slug }
        }
    
    if (Array.isArray(args)) {
        args = {
                    gallery: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        gallery: typeof args.gallery === 'object'
                ? args.gallery.slug
                : args.gallery,
                }

    return show.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
show.get = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
show.head = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
    const showForm = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
        showForm.get = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\GalleryController::show
 * @see app/Http/Controllers/Public/GalleryController.php:22
 * @route '/galleries/{gallery}'
 */
        showForm.head = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const galleries = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default galleries