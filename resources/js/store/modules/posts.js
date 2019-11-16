const namespaced = true;

const state = {
	all: [],
    selected: {},
    searchMeta : [],
    searchResult : {}
}

const getters = {

}

const mutations = {
    SETPOSTS: (state, $posts) => {
        if(_.isEmpty($posts)) return;
		state.all = $posts;
    },
    ADDPOSTS: (state, $post) => {
        let all = _.reverse(state.all);
        all.push($post);
        state.all = _.reverse(state.all);
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
    },
    SEARCHPOSTS: (state, $search) => {
        if ($search) {
            let metaKey = _.findIndex(state.searchMeta, function(meta) { return meta == $search; });
            if (metaKey == -1) {
                    state.searchMeta.push($search);
            }
        }

        if(_.isEmpty(state.searchMeta)) {
            state.searchResult = {};
            return;
        }

        let results = state.all.filter(obj => Object.values(obj).some(
            function (val) {
                if (typeof val == 'string') {
                    var hay = val.toLowerCase().replace(/(<([^>]+)>)/ig,"");
                    var selected = false;
                    state.searchMeta.some(function(search) {
                        if(hay.includes(search.toLowerCase())) {
                            selected = true;
                            return;
                        }
                    });

                    if(selected) return val;
                }
            }
        ));
        if(_.isEmpty(results)) {
            state.searchResult = [];
            return;
        }
        state.searchResult = results;
    },
    REMOVEMETA: (state, $meta) => {
        _.pull(state.searchMeta, $meta);
    }
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
