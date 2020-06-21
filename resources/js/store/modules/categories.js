let CATEGORIES_FUNC = (function() {
    let _clone = (toClone) => {
        return  Object.assign({}, toClone);
    }

    return {
        clone: ($toClone) => {
            return _clone($toClone);
        }
    }
})();

const namespaced = true;

const state = {
    all: [],
    selected: {},

}

const getters = {
    categories: (state) => state.all
}

const mutations = {
    SETCATEGORIES: (state, $categories) => {
        state.all = $categories;
    },
    ADDCATEGORY: (state, $category) => {
        let all = _.reverse(state.all);
        all.push($category);
        state.all = _.reverse(state.all);
    },
    UPDATECATEGORY: (state, $category) => {
        if(_.isEmpty(state.all))  {
            state.all[0] = $category;
            return;
        }

        let categoryKey = _.findIndex(state.all, function(category) { return category.id == $category.id; });

        if(categoryKey == -1) return;
        state.all[categoryKey] = $category;
    },
    SETSELECTEDCATEGORY: (state, $category) => {
        state.selected = $category;
    },
    SETSELECTEDCATEGORYBYID: (state, $id) => {
        state.selected = {};

        if (!$id) return;
        let categoryKey = _.findIndex(state.all, function(category) { return category.id == $id; });

        if(categoryKey == -1) return;
        state.selected = CATEGORIES_FUNC.clone(state.all[categoryKey]);
    },
    REMOVECATEGORY: (state, $id) => {
        let categoryKey = _.findIndex(state.all, function(category) { return category.id == $id; });

        if(categoryKey == -1) return;
        state.all.splice(categoryKey, 1);
    },
    UPDATECHILDCATEGORYAFTERPARENTREMOVE : (state, $id) => {
        _.forEach(state.all, function(category, index) {
          if(category.parent_id == $id) {
                state.all[index].parent_id = 0;
          }
        });
    },

}

const actions = {
    setcategories (context) {
        return new Promise((resolve, reject) => {

            client.get('/api/v1/category')
            .then((response) => {
                if(!_.isEmpty(response.data.categories)) {
                   context.commit('SETCATEGORIES', response.data.categories);
                }
                resolve(response);
            })
            .catch((error) => {
                console.log(error);
                reject(error);
            });

        })
    },
    addCategory (context, formData) {

        return new Promise((resolve, reject) => {

            client.post('/api/v1/category', formData)
            .then((response) => {
                if(!_.isEmpty(response.data.category)) {
                   context.commit('ADDCATEGORY', response.data.category);
                }
                resolve(response);
            })
            .catch((error) => {
                console.log(error);
                reject(error);
            });

        })
    },
    updateCategory (context, formData) {

        return new Promise((resolve, reject) => {
            client.post('/api/v1/category/' + context.state.selected.id, formData)
            .then((response) => {

                context.commit('UPDATECATEGORY', response.data.category);

                resolve(response);
            })
            .catch((error) => {
                console.log('error ',  error);

                reject(error);
            });
        })
    },
    removeCategory (context, value) {
        context.commit('REMOVECATEGORY', value)
    },
    setSelectedCategory (context, value) {
        if(!value) value = {};
        context.commit('SETSELECTEDCATEGORY', value)
    },
    setSelectedCategoryById (context, value) {
        context.commit('SETSELECTEDCATEGORYBYID', value)
    },
    updateChildCategoryAfterParentRemove (context, value) {
        context.commit('UPDATECHILDCATEGORYAFTERPARENTREMOVE', value)
    },
}

const module = {
    namespaced,
    state,
    getters,
    mutations,
    actions
};



export default module;
