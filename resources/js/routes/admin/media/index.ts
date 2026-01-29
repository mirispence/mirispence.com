import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
export const original = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: original.url(args, options),
    method: 'get',
})

original.definition = {
    methods: ["get","head"],
    url: '/admin/media/{media}/original',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
original.url = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return original.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
original.get = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: original.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
original.head = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: original.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
    const originalForm = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: original.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
        originalForm.get = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: original.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\OriginalMediaController::original
 * @see app/Http/Controllers/Admin/OriginalMediaController.php:15
 * @route '/admin/media/{media}/original'
 */
        originalForm.head = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: original.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    original.form = originalForm
const media = {
    original: Object.assign(original, original),
}

export default media