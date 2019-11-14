const namespaced = true;

const state = {
	all: {},
	active: {},
	activeDetails: {},
    selectedUser: {},
    selectedDetails: {}
}

const getters = {
}

const mutations = {
	SETUSERS: (state, $users) => {
        if(_.isEmpty($users)) return;
		state.all = $users;
    },
	SETACTIVE: (state, $user) => {
        if(_.isEmpty($user)) return;
		state.active = $user;

        let userKey = _.findIndex(state.all, function(user) { return user.id == state.active.id; });

		if(userKey == -1) return;
		state.all[userKey].name = $user.name;
        if($user.profile_image) state.all[userKey].profile_image = $user.profile_image;
    },
	SETACTIVEDETAILS: (state, $details) => {
		if(_.isEmpty($details)) return;
		state.activeDetails = $details;
    },
	SETSELECTEDUSER: (state, $id) => {
		if(!$id) return;
		let user = _.find(state.all, function(user) { return user.id == $id; });
		let _details =  _.pick(user, 'details');

		let details =  {};
		_.forEach(_details.details, function(detail) {
			  details[detail.key] = detail.value;
		});

		state.selectedUser = _.omit(user, 'details');
        state.selectedDetails = details;
    },
	GETUSERNAMEBYID: (state, $id) => {
		if(!$id) return;
		let user = _.find(state.all, function(user) { return user.id == $id; });
         state.whitelabelCreatedBy = (!_.isEmpty(user)) ? user.name : '';
	 }
}

const actions = {
	setUsers (context, value) {
        context.commit('SETUSERS', value)
	},
	setActiveUser (context, value) {
        context.commit('SETACTIVE', value)
	},
	setActiveUserDetails (context, value) {
        context.commit('SETACTIVEDETAILS', value)
	},
	setSelectedUser (context, value) {
        context.commit('SETSELECTEDUSER', value)
	},
	getUserNameByID (context, value) {
        context.commit('GETUSERNAMEBYID', value)
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
