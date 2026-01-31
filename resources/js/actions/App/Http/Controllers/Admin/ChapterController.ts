import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/chapters',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ChapterController::index
 * @see app/Http/Controllers/Admin/ChapterController.php:17
 * @route '/admin/chapters'
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
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/chapters/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ChapterController::create
 * @see app/Http/Controllers/Admin/ChapterController.php:40
 * @route '/admin/chapters/create'
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
* @see \App\Http\Controllers\Admin\ChapterController::store
 * @see app/Http/Controllers/Admin/ChapterController.php:50
 * @route '/admin/chapters'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/chapters',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::store
 * @see app/Http/Controllers/Admin/ChapterController.php:50
 * @route '/admin/chapters'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::store
 * @see app/Http/Controllers/Admin/ChapterController.php:50
 * @route '/admin/chapters'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::store
 * @see app/Http/Controllers/Admin/ChapterController.php:50
 * @route '/admin/chapters'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::store
 * @see app/Http/Controllers/Admin/ChapterController.php:50
 * @route '/admin/chapters'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
export const show = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/chapters/{chapter}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
show.url = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { chapter: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    chapter: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        chapter: args.chapter,
                }

    return show.definition.url
            .replace('{chapter}', parsedArgs.chapter.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
show.get = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
show.head = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
    const showForm = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
        showForm.get = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ChapterController::show
 * @see app/Http/Controllers/Admin/ChapterController.php:71
 * @route '/admin/chapters/{chapter}'
 */
        showForm.head = (args: { chapter: string | number } | [chapter: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
export const edit = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/chapters/{chapter}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
edit.url = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { chapter: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { chapter: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    chapter: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        chapter: typeof args.chapter === 'object'
                ? args.chapter.id
                : args.chapter,
                }

    return edit.definition.url
            .replace('{chapter}', parsedArgs.chapter.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
edit.get = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
edit.head = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
    const editForm = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
        editForm.get = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ChapterController::edit
 * @see app/Http/Controllers/Admin/ChapterController.php:76
 * @route '/admin/chapters/{chapter}/edit'
 */
        editForm.head = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
export const update = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/chapters/{chapter}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
update.url = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { chapter: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { chapter: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    chapter: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        chapter: typeof args.chapter === 'object'
                ? args.chapter.id
                : args.chapter,
                }

    return update.definition.url
            .replace('{chapter}', parsedArgs.chapter.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
update.put = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
update.patch = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
    const updateForm = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
        updateForm.put = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\ChapterController::update
 * @see app/Http/Controllers/Admin/ChapterController.php:86
 * @route '/admin/chapters/{chapter}'
 */
        updateForm.patch = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\ChapterController::destroy
 * @see app/Http/Controllers/Admin/ChapterController.php:109
 * @route '/admin/chapters/{chapter}'
 */
export const destroy = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/chapters/{chapter}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ChapterController::destroy
 * @see app/Http/Controllers/Admin/ChapterController.php:109
 * @route '/admin/chapters/{chapter}'
 */
destroy.url = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { chapter: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { chapter: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    chapter: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        chapter: typeof args.chapter === 'object'
                ? args.chapter.id
                : args.chapter,
                }

    return destroy.definition.url
            .replace('{chapter}', parsedArgs.chapter.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ChapterController::destroy
 * @see app/Http/Controllers/Admin/ChapterController.php:109
 * @route '/admin/chapters/{chapter}'
 */
destroy.delete = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ChapterController::destroy
 * @see app/Http/Controllers/Admin/ChapterController.php:109
 * @route '/admin/chapters/{chapter}'
 */
    const destroyForm = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ChapterController::destroy
 * @see app/Http/Controllers/Admin/ChapterController.php:109
 * @route '/admin/chapters/{chapter}'
 */
        destroyForm.delete = (args: { chapter: number | { id: number } } | [chapter: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const ChapterController = { index, create, store, show, edit, update, destroy }

export default ChapterController