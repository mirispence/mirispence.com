import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
export const conversion = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: conversion.url(args, options),
    method: 'get',
})

conversion.definition = {
    methods: ["get","head"],
    url: '/i/{media}/{conversion}/{filename?}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
conversion.url = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions) => {
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

    return conversion.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace('{conversion}', parsedArgs.conversion.toString())
            .replace('{filename?}', parsedArgs.filename?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
conversion.get = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: conversion.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
conversion.head = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: conversion.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
    const conversionForm = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: conversion.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
        conversionForm.get = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: conversion.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MediaConversionController::conversion
 * @see app/Http/Controllers/MediaConversionController.php:15
 * @route '/i/{media}/{conversion}/{filename?}'
 */
        conversionForm.head = (args: { media: number | { id: number }, conversion: string | number, filename?: string | number } | [media: number | { id: number }, conversion: string | number, filename: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: conversion.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    conversion.form = conversionForm
const media = {
    conversion: Object.assign(conversion, conversion),
}

export default media