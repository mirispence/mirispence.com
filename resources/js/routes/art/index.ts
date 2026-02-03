import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
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
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
 * @route '/art'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
 * @route '/art'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
 * @route '/art'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
 * @route '/art'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
 * @route '/art'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\ArtworkController::index
 * @see app/Http/Controllers/Public/ArtworkController.php:16
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
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
export const show = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/art/{artwork}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
show.url = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
            args = { artwork: args.slug }
        }
    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: typeof args.artwork === 'object'
                ? args.artwork.slug
                : args.artwork,
                }

    return show.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
show.get = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
show.head = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
    const showForm = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
        showForm.get = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\ArtworkController::show
 * @see app/Http/Controllers/Public/ArtworkController.php:42
 * @route '/art/{artwork}'
 */
        showForm.head = (args: { artwork: string | { slug: string } } | [artwork: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const art = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default art