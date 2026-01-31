import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/artworks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ArtworkController::index
 * @see app/Http/Controllers/Admin/ArtworkController.php:18
 * @route '/admin/artworks'
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
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/artworks/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ArtworkController::create
 * @see app/Http/Controllers/Admin/ArtworkController.php:29
 * @route '/admin/artworks/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::store
 * @see app/Http/Controllers/Admin/ArtworkController.php:39
 * @route '/admin/artworks'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/artworks',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::store
 * @see app/Http/Controllers/Admin/ArtworkController.php:39
 * @route '/admin/artworks'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::store
 * @see app/Http/Controllers/Admin/ArtworkController.php:39
 * @route '/admin/artworks'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::store
 * @see app/Http/Controllers/Admin/ArtworkController.php:39
 * @route '/admin/artworks'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::store
 * @see app/Http/Controllers/Admin/ArtworkController.php:39
 * @route '/admin/artworks'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
export const show = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/artworks/{artwork}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
show.url = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: args.artwork,
                }

    return show.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
show.get = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
show.head = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
    const showForm = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
        showForm.get = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ArtworkController::show
 * @see app/Http/Controllers/Admin/ArtworkController.php:79
 * @route '/admin/artworks/{artwork}'
 */
        showForm.head = (args: { artwork: string | number } | [artwork: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
export const edit = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/artworks/{artwork}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
edit.url = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { artwork: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: typeof args.artwork === 'object'
                ? args.artwork.id
                : args.artwork,
                }

    return edit.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
edit.get = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
edit.head = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
    const editForm = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
        editForm.get = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ArtworkController::edit
 * @see app/Http/Controllers/Admin/ArtworkController.php:84
 * @route '/admin/artworks/{artwork}/edit'
 */
        editForm.head = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
export const update = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/artworks/{artwork}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
update.url = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { artwork: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: typeof args.artwork === 'object'
                ? args.artwork.id
                : args.artwork,
                }

    return update.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
update.put = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
update.patch = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
    const updateForm = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
        updateForm.put = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\ArtworkController::update
 * @see app/Http/Controllers/Admin/ArtworkController.php:95
 * @route '/admin/artworks/{artwork}'
 */
        updateForm.patch = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::destroy
 * @see app/Http/Controllers/Admin/ArtworkController.php:138
 * @route '/admin/artworks/{artwork}'
 */
export const destroy = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/artworks/{artwork}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::destroy
 * @see app/Http/Controllers/Admin/ArtworkController.php:138
 * @route '/admin/artworks/{artwork}'
 */
destroy.url = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { artwork: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: typeof args.artwork === 'object'
                ? args.artwork.id
                : args.artwork,
                }

    return destroy.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::destroy
 * @see app/Http/Controllers/Admin/ArtworkController.php:138
 * @route '/admin/artworks/{artwork}'
 */
destroy.delete = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::destroy
 * @see app/Http/Controllers/Admin/ArtworkController.php:138
 * @route '/admin/artworks/{artwork}'
 */
    const destroyForm = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::destroy
 * @see app/Http/Controllers/Admin/ArtworkController.php:138
 * @route '/admin/artworks/{artwork}'
 */
        destroyForm.delete = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::regenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:148
 * @route '/admin/artworks/{artwork}/regenerate'
 */
export const regenerate = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: regenerate.url(args, options),
    method: 'post',
})

regenerate.definition = {
    methods: ["post"],
    url: '/admin/artworks/{artwork}/regenerate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::regenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:148
 * @route '/admin/artworks/{artwork}/regenerate'
 */
regenerate.url = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { artwork: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { artwork: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    artwork: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        artwork: typeof args.artwork === 'object'
                ? args.artwork.id
                : args.artwork,
                }

    return regenerate.definition.url
            .replace('{artwork}', parsedArgs.artwork.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::regenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:148
 * @route '/admin/artworks/{artwork}/regenerate'
 */
regenerate.post = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: regenerate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::regenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:148
 * @route '/admin/artworks/{artwork}/regenerate'
 */
    const regenerateForm = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: regenerate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::regenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:148
 * @route '/admin/artworks/{artwork}/regenerate'
 */
        regenerateForm.post = (args: { artwork: number | { id: number } } | [artwork: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: regenerate.url(args, options),
            method: 'post',
        })
    
    regenerate.form = regenerateForm
/**
* @see \App\Http\Controllers\Admin\ArtworkController::bulkRegenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:157
 * @route '/admin/artworks/bulk-regenerate'
 */
export const bulkRegenerate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkRegenerate.url(options),
    method: 'post',
})

bulkRegenerate.definition = {
    methods: ["post"],
    url: '/admin/artworks/bulk-regenerate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ArtworkController::bulkRegenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:157
 * @route '/admin/artworks/bulk-regenerate'
 */
bulkRegenerate.url = (options?: RouteQueryOptions) => {
    return bulkRegenerate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ArtworkController::bulkRegenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:157
 * @route '/admin/artworks/bulk-regenerate'
 */
bulkRegenerate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkRegenerate.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ArtworkController::bulkRegenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:157
 * @route '/admin/artworks/bulk-regenerate'
 */
    const bulkRegenerateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkRegenerate.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ArtworkController::bulkRegenerate
 * @see app/Http/Controllers/Admin/ArtworkController.php:157
 * @route '/admin/artworks/bulk-regenerate'
 */
        bulkRegenerateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkRegenerate.url(options),
            method: 'post',
        })
    
    bulkRegenerate.form = bulkRegenerateForm
const artworks = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
regenerate: Object.assign(regenerate, regenerate),
bulkRegenerate: Object.assign(bulkRegenerate, bulkRegenerate),
}

export default artworks