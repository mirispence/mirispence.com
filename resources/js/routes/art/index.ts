import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/art',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\GalleryController::index
 * @see app/Http/Controllers/Public/GalleryController.php:14
 * @route '/art'
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
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
export const gallery = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: gallery.url(args, options),
    method: 'get',
})

gallery.definition = {
    methods: ["get","head"],
    url: '/art/{gallery}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
gallery.url = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return gallery.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
gallery.get = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: gallery.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
gallery.head = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: gallery.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
    const galleryForm = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: gallery.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
        galleryForm.get = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: gallery.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\GalleryController::gallery
 * @see app/Http/Controllers/Public/GalleryController.php:26
 * @route '/art/{gallery}'
 */
        galleryForm.head = (args: { gallery: string | { slug: string } } | [gallery: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: gallery.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    gallery.form = galleryForm
const art = {
    index: Object.assign(index, index),
gallery: Object.assign(gallery, gallery),
}

export default art