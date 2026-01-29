import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
export const show = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/i/{media}/{conversion}/{filename?}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
show.url = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    media: args[0],
                    conversion: args[1],
                    filename: args[2],
                }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
            "filename",
        ])

    const parsedArgs = {
                        media: typeof args.media === 'object'
                ? args.media.id
                : args.media,
                                conversion: args.conversion,
                                filename: args.filename,
                }

    return show.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace('{conversion}', parsedArgs.conversion.toString())
            .replace('{filename?}', parsedArgs.filename?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
show.get = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
show.head = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
    const showForm = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
        showForm.get = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MediaConversionController::show
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
        showForm.head = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const MediaConversionController = { show }

export default MediaConversionController