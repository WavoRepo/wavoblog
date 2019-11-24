function paginate (array, page_size, page_number) {
    --page_number; // because pages logically start with 1, but technically with 0
    return array.slice(page_number * page_size, (page_number + 1) * page_size);
}

function sortOwner (prop, sortable, sort_type) {
    prop = prop.split('.');
    let len = prop.length;

    sortable.sort(function (prop_a, prop_b) {
        let i = 0;
        // Get the correct value
        while( i < len ) {
            prop_a = prop_a[prop[i]];
            prop_b = prop_b[prop[i]];
            i++;
        }

        if (prop_a < prop_b) {
            if(sort_type == 'desc') return 1;
            return -1;
        } else if (prop_a > prop_b) {
            if(sort_type == 'desc') return -1;
            return 1;
        } else {
            return 0;
        }
    });
    return sortable;
};

function search($searchable, $metas) {
    return $searchable.filter(obj => Object.values(obj).some(
        function (val) {
            if (val && (typeof val == 'string' || typeof val == 'object')) {
                let hay = '';
                if(typeof val === 'object') hay = val.name.toLowerCase().replace(/(<([^>]+)>)/ig,"");
                else hay = val.toLowerCase().replace(/(<([^>]+)>)/ig,"");

                let selected = false;
                $metas.some(function(search) {
                    if(hay.includes(search.toLowerCase())) {
                        selected = true;
                        return;
                    }
                });

                if(selected) return val;
            }
        }
    ));
}

const namespaced = true;

const state = {
    all: [],
    searchMeta : [],
    searchResult : [],
    paginate : [],
    sorted: [],
    selected: {},
    perPage : (sessionStorage.getItem('post-per-page')) ? parseInt(sessionStorage.getItem('post-per-page')) : 9,
    pageNum : (sessionStorage.getItem('post-page-num')) ? parseInt(sessionStorage.getItem('post-page-num')) : 1,
    doPagination: (sessionStorage.getItem('do-pagination') == 'true') ? true : false,
    sortBy : 'index',
    sortDir: '',
}

const getters = {

}

const mutations = {
    SETPOSTS: (state, $posts) => {
        if(_.isEmpty($posts)) return;
        state.all = $posts;

        if(state.doPagination) state.paginate = paginate ($posts, state.perPage, state.pageNum);
    },
    ADDPOSTS: (state, $post) => {
        let all = _.reverse(state.all);
        all.push($post);
        state.all = _.reverse(state.all);

        if(state.doPagination) state.paginate = paginate ($post, state.perPage, state.pageNum);
    },
    UPDATEPOSTS: (state, $post) => {
        if(_.isEmpty(state.all))  {
            state.all[0] = $post;
            return;
        }

        if(_.isEmpty($post)) return;
        let userKey = _.findIndex(state.all, function(post) { return post.id == $post.id; });

        if(userKey == -1) return;
        state.all[userKey] = $post;

        if(state.doPagination) state.paginate = paginate ($posts, state.perPage, state.pageNum);
    },
    SETSELECTEDPOST: (state, $post) => {
        state.selected = $post;
        sessionStorage.setItem('selected-post', JSON.stringify(state.selected));
    },
    SETSELECTEDPOSTBYID: (state, $id) => {
        let userKey = _.findIndex(state.all, function(post) { return post.id == $id; });

        if(userKey == -1) return;
        state.selected = state.all[userKey];
        sessionStorage.setItem('selected-post', JSON.stringify(state.selected));
    },
    REMOVEPOST: (state, $id) => {
        let userKey = _.findIndex(state.all, function(post) { return post.id == $id; });

        if(userKey == -1) return;
        state.all.splice(userKey, 1);

        if(state.doPagination) state.paginate = paginate (state.all, state.perPage, state.pageNum);
    },
    SEARCHPOSTS: (state, $search) => {
        state.pageNum = 1;
        if ($search) {
            let metaKey = _.findIndex(state.searchMeta, function(meta) { return meta == $search; });
            if (metaKey == -1) {
                state.searchMeta.push($search);
            }
        }

        if(_.isEmpty(state.searchMeta)) {
            state.sortBy = '';
            state.searchResult = {};

            if(state.doPagination) state.paginate = paginate (state.all, state.perPage, state.pageNum);
            return;
        }

        let results = search(state.all, state.searchMeta);

        if(_.isEmpty(results)) {
            state.searchResult = [];
            return;
        }
        state.searchResult = results;

        if(state.doPagination) state.paginate = paginate (state.searchResult, state.perPage, state.pageNum);
    },
    REMOVEMETA: (state, $meta) => {
        _.pull(state.searchMeta, $meta);
    },
    SETPAGENUM: (state, $pageNum) => {
        if($pageNum) {
            state.pageNum = $pageNum;

            sessionStorage.setItem('post-page-num', state.pageNum);

            if(!state.doPagination) return;

            if(!_.isEmpty(state.searchResult)) state.paginate = paginate (state.searchResult, state.perPage, state.pageNum);
            else state.paginate = paginate (state.all, state.perPage, state.pageNum);
        }
    },
    SORT: (state, $data) => {
        state.sortBy = $data.by;

        if(!$data.type) return;

        state.sortDir = $data.type;

        if(state.sortBy == 'index') {
            return;
        }

        let sortable = [];
        if(!_.isEmpty(state.searchResult)) sortable = state.searchResult;
        else sortable = state.all;

        if(state.sortBy == 'owner') {
            sortable = sortOwner('owner.name', sortable, $data.type) // Use Lodash to sort array by 'field name'
        } else {
            sortable = _.orderBy(sortable, $data.by, $data.type); // Use Lodash to sort array by 'field name'
        }

        if(sortable.length == 0) return;

        state.sorted = sortable;

        if(state.doPagination) state.paginate = paginate (sortable, state.perPage, state.pageNum);
    },
    CHANGEPERPAGE: (state, $perpage) => {
        state.perPage = $perpage;

        sessionStorage.setItem('post-per-page', state.perPage);

        if(!state.doPagination)  return;

        if(!_.isEmpty(state.searchResult)) state.paginate = paginate (state.searchResult, state.perPage, state.pageNum);
        else state.paginate = paginate (state.all, state.perPage, state.pageNum);
    },
    TOGGLEPAGINATION: (state, $paginate) => {
        state.doPagination = !state.doPagination;

        sessionStorage.setItem('do-pagination', state.doPagination);

        if(!state.doPagination) return;

        if(!_.isEmpty(state.sorted)) {
            state.paginate = paginate (state.sorted, state.perPage, state.pageNum);
            return;
        }
        if(!_.isEmpty(state.searchResult)) {
            state.paginate = paginate (state.searchResult, state.perPage, state.pageNum);
            return;
        }
        if(!_.isEmpty(state.all)) {
            state.paginate = paginate (state.all, state.perPage, state.pageNum);
            return;
        }
    },
}

const actions = {
    setPosts (context, value) {
        context.commit('SETPOSTS', value)
    },
    addPosts (context, value) {
        context.commit('ADDPOSTS', value)
    },
    updatePosts (context, value) {
        context.commit('UPDATEPOSTS', value)
    },
    setSelectedPost (context, value) {
        context.commit('SETSELECTEDPOST', value)
    },
    setSelectedPostById (context, value) {
        context.commit('SETSELECTEDPOSTBYID', value)
    },
    removePost (context, value) {
        context.commit('REMOVEPOST', value)
    },
    searchPosts (context, value) {
        context.commit('SEARCHPOSTS', value)
    },
    removeMeta (context, value) {
        context.commit('REMOVEMETA', value)
    },
    setPageNum (context, value) {
        context.commit('SETPAGENUM', value)
    },
    sort (context, value) {
        context.commit('SORT', value)
    },
    changePerPage (context, value) {
        context.commit('CHANGEPERPAGE', value)
    },
    togglePagination (context, value) {
        context.commit('TOGGLEPAGINATION', value)
    }
}

const module = {
    namespaced,
    state,
    getters,
    mutations,
    actions
};



export default module;
