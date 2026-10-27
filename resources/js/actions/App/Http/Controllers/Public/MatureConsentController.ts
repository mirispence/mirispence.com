import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Public\MatureConsentController::store
 * @see app/Http/Controllers/Public/MatureConsentController.php:16
 * @route '/mature-consent'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/mature-consent',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Public\MatureConsentController::store
 * @see app/Http/Controllers/Public/MatureConsentController.php:16
 * @route '/mature-consent'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\MatureConsentController::store
 * @see app/Http/Controllers/Public/MatureConsentController.php:16
 * @route '/mature-consent'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Public\MatureConsentController::store
 * @see app/Http/Controllers/Public/MatureConsentController.php:16
 * @route '/mature-consent'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Public\MatureConsentController::store
 * @see app/Http/Controllers/Public/MatureConsentController.php:16
 * @route '/mature-consent'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const MatureConsentController = { store }

export default MatureConsentController