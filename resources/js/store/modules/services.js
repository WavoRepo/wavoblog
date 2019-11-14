const namespaced = true;

const state = {
	all: {}
}

const getters = {
}

const mutations = {
	SET: (state, $services) => {
		state.all = $services;
    }
}

const actions = {
	setServices (context, value) {
        context.commit('SET', value)
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
