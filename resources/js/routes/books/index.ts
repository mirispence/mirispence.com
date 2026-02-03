import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/books',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\BookController::index
 * @see app/Http/Controllers/Public/BookController.php:14
 * @route '/books'
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
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
export const show = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/books/{book}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
show.url = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { book: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
            args = { book: args.slug }
        }
    
    if (Array.isArray(args)) {
        args = {
                    book: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        book: typeof args.book === 'object'
                ? args.book.slug
                : args.book,
                }

    return show.definition.url
            .replace('{book}', parsedArgs.book.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
show.get = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
show.head = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
    const showForm = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
        showForm.get = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Public\BookController::show
 * @see app/Http/Controllers/Public/BookController.php:23
 * @route '/books/{book}'
 */
        showForm.head = (args: { book: string | { slug: string } } | [book: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const books = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default books