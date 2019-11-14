const namespaced = true;

const state = {
	all: [],
    selected: {}
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
