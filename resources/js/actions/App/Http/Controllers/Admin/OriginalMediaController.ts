import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
export const show = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/media/{media}/original',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
show.url = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { media: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { media: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    media: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        media: typeof args.media === 'object'
                ? args.media.id
                : args.media,
                }

    return show.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
show.get = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
show.head = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
    const showForm = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
        showForm.get = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::show
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
        showForm.head = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const OriginalMediaController = { show }

export default OriginalMediaController