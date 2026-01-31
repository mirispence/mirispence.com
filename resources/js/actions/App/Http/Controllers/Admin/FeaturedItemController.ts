import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/featured-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::index
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:15
 * @route '/admin/featured-items'
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
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/featured-items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::create
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:27
 * @route '/admin/featured-items/create'
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
* @see \App\Http\Controllers\Admin\FeaturedItemController::store
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:37
 * @route '/admin/featured-items'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/featured-items',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::store
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:37
 * @route '/admin/featured-items'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::store
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:37
 * @route '/admin/featured-items'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::store
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:37
 * @route '/admin/featured-items'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::store
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:37
 * @route '/admin/featured-items'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
export const show = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/featured-items/{featured_item}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
show.url = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { featured_item: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    featured_item: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        featured_item: args.featured_item,
                }

    return show.definition.url
            .replace('{featured_item}', parsedArgs.featured_item.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
show.get = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
show.head = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
    const showForm = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
        showForm.get = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::show
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:54
 * @route '/admin/featured-items/{featured_item}'
 */
        showForm.head = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
export const edit = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/featured-items/{featured_item}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
edit.url = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { featured_item: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    featured_item: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        featured_item: args.featured_item,
                }

    return edit.definition.url
            .replace('{featured_item}', parsedArgs.featured_item.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
edit.get = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
edit.head = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
    const editForm = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
        editForm.get = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::edit
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:59
 * @route '/admin/featured-items/{featured_item}/edit'
 */
        editForm.head = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
export const update = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/featured-items/{featured_item}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
update.url = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { featured_item: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    featured_item: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        featured_item: args.featured_item,
                }

    return update.definition.url
            .replace('{featured_item}', parsedArgs.featured_item.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
update.put = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
update.patch = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
    const updateForm = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
        updateForm.put = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::update
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:70
 * @route '/admin/featured-items/{featured_item}'
 */
        updateForm.patch = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\FeaturedItemController::destroy
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:87
 * @route '/admin/featured-items/{featured_item}'
 */
export const destroy = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/featured-items/{featured_item}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::destroy
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:87
 * @route '/admin/featured-items/{featured_item}'
 */
destroy.url = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { featured_item: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    featured_item: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        featured_item: args.featured_item,
                }

    return destroy.definition.url
            .replace('{featured_item}', parsedArgs.featured_item.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\FeaturedItemController::destroy
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:87
 * @route '/admin/featured-items/{featured_item}'
 */
destroy.delete = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::destroy
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:87
 * @route '/admin/featured-items/{featured_item}'
 */
    const destroyForm = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\FeaturedItemController::destroy
 * @see app/Http/Controllers/Admin/FeaturedItemController.php:87
 * @route '/admin/featured-items/{featured_item}'
 */
        destroyForm.delete = (args: { featured_item: string | number } | [featured_item: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const FeaturedItemController = { index, create, store, show, edit, update, destroy }

export default FeaturedItemController