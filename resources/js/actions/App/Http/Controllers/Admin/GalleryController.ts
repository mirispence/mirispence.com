import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/galleries',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\GalleryController::index
 * @see app/Http/Controllers/Admin/GalleryController.php:16
 * @route '/admin/galleries'
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
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/galleries/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\GalleryController::create
 * @see app/Http/Controllers/Admin/GalleryController.php:25
 * @route '/admin/galleries/create'
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
* @see \App\Http\Controllers\Admin\GalleryController::store
 * @see app/Http/Controllers/Admin/GalleryController.php:30
 * @route '/admin/galleries'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/galleries',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::store
 * @see app/Http/Controllers/Admin/GalleryController.php:30
 * @route '/admin/galleries'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::store
 * @see app/Http/Controllers/Admin/GalleryController.php:30
 * @route '/admin/galleries'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::store
 * @see app/Http/Controllers/Admin/GalleryController.php:30
 * @route '/admin/galleries'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::store
 * @see app/Http/Controllers/Admin/GalleryController.php:30
 * @route '/admin/galleries'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
export const show = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/galleries/{gallery}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
show.url = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { gallery: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    gallery: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        gallery: args.gallery,
                }

    return show.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
show.get = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
show.head = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
    const showForm = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
        showForm.get = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\GalleryController::show
 * @see app/Http/Controllers/Admin/GalleryController.php:53
 * @route '/admin/galleries/{gallery}'
 */
        showForm.head = (args: { gallery: string | number } | [gallery: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
export const edit = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/galleries/{gallery}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
edit.url = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { gallery: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { gallery: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    gallery: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        gallery: typeof args.gallery === 'object'
                ? args.gallery.id
                : args.gallery,
                }

    return edit.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
edit.get = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
edit.head = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
    const editForm = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
        editForm.get = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\GalleryController::edit
 * @see app/Http/Controllers/Admin/GalleryController.php:58
 * @route '/admin/galleries/{gallery}/edit'
 */
        editForm.head = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
export const update = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/galleries/{gallery}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
update.url = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { gallery: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { gallery: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    gallery: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        gallery: typeof args.gallery === 'object'
                ? args.gallery.id
                : args.gallery,
                }

    return update.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
update.put = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
update.patch = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
    const updateForm = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
        updateForm.put = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\GalleryController::update
 * @see app/Http/Controllers/Admin/GalleryController.php:65
 * @route '/admin/galleries/{gallery}'
 */
        updateForm.patch = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\GalleryController::destroy
 * @see app/Http/Controllers/Admin/GalleryController.php:91
 * @route '/admin/galleries/{gallery}'
 */
export const destroy = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/galleries/{gallery}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\GalleryController::destroy
 * @see app/Http/Controllers/Admin/GalleryController.php:91
 * @route '/admin/galleries/{gallery}'
 */
destroy.url = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { gallery: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { gallery: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    gallery: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        gallery: typeof args.gallery === 'object'
                ? args.gallery.id
                : args.gallery,
                }

    return destroy.definition.url
            .replace('{gallery}', parsedArgs.gallery.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GalleryController::destroy
 * @see app/Http/Controllers/Admin/GalleryController.php:91
 * @route '/admin/galleries/{gallery}'
 */
destroy.delete = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\GalleryController::destroy
 * @see app/Http/Controllers/Admin/GalleryController.php:91
 * @route '/admin/galleries/{gallery}'
 */
    const destroyForm = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\GalleryController::destroy
 * @see app/Http/Controllers/Admin/GalleryController.php:91
 * @route '/admin/galleries/{gallery}'
 */
        destroyForm.delete = (args: { gallery: number | { id: number } } | [gallery: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const GalleryController = { index, create, store, show, edit, update, destroy }

export default GalleryController